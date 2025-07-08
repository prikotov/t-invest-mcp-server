<?php

declare(strict_types=1);

namespace App\Tool\GetAssetFundamentals;

use App\Component\InstrumentsService\Dto\GetAssetFundamentalsRequestDto;
use App\Component\InstrumentsService\InstrumentsServiceComponentInterface;
use App\Enum\ToolNameEnum;
use App\Exception\InfrastructureExceptionInterface;
use App\Service\Instruments\InstrumentsServiceInterface;
use App\Service\TextContentSerializer\TextContentSerializerServiceInterface;
use App\Tool\GetAssetFundamentals\Mapper\GetAssetFundamentalsMapper;
use App\Tool\ToolInterface;
use Mcp\Types\CallToolResult;
use Mcp\Types\TextContent;
use Mcp\Types\Tool;
use Mcp\Types\ToolInputProperties;
use Mcp\Types\ToolInputSchema;
use Override;

final readonly class GetAssetFundamentalsTool implements ToolInterface
{
    private const string PARAMETER_TICKERS = 'tickers';

    public function __construct(
        private InstrumentsServiceComponentInterface $instrumentsServiceComponent,
        private InstrumentsServiceInterface $instrumentsService,
        private GetAssetFundamentalsMapper $mapper,
        private TextContentSerializerServiceInterface $serializer,
    ) {
    }

    #[Override]
    public function getName(): string
    {
        return ToolNameEnum::getAssetFundamentals->value;
    }

    #[Override]
    public function getDescription(): string
    {
        return 'Возвращает фундаментальные показатели компаний по заданным тикерам';
    }

    #[Override]
    public function getTool(): Tool
    {
        $properties = ToolInputProperties::fromArray([
            self::PARAMETER_TICKERS => [
                'type' => 'array',
                'items' => ['type' => 'string'],
                'description' => 'Массив тикеров инструментов, не более 100 шт.',
            ],
        ]);

        $inputSchema = new ToolInputSchema(
            properties: $properties,
        );

        return new Tool(
            name: $this->getName(),
            inputSchema: $inputSchema,
            description: $this->getDescription(),
        );
    }

    #[Override]
    public function __invoke(mixed ...$args): CallToolResult
    {
        $params = $args[0] ?? [];
        $tickers = $params[self::PARAMETER_TICKERS] ?? [];
        if (is_string($tickers)) {
            $tickers = array_map('trim', explode(',', $tickers));
        }
        if (!is_array($tickers)) {
            $tickers = [];
        }

        $assets = [];
        foreach ($tickers as $ticker) {
            $uid = $this->instrumentsService->getAssetUidByTicker($ticker);
            if ($uid !== null) {
                $assets[] = $uid;
            }
        }

        try {
            $componentDto = $this->instrumentsServiceComponent->getAssetFundamentals(new GetAssetFundamentalsRequestDto($assets));
        } catch (InfrastructureExceptionInterface $e) {
            return new CallToolResult(
                content: [new TextContent(text: 'Unable to fetch data from T-Invest API: ' . $e->getMessage())],
                isError: true,
            );
        }

        $dto = $this->mapper->map($componentDto);
        $text = $this->serializer->serialize($dto);

        return new CallToolResult(content: [new TextContent(text: $text)]);
    }
}
