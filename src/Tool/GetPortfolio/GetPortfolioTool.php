<?php

declare(strict_types=1);

namespace App\Tool\GetPortfolio;

use App\Component\OperationsService\Dto\GetPortfolioRequestDto;
use App\Component\OperationsService\OperationsServiceComponentInterface;
use App\Enum\ToolNameEnum;
use App\Exception\InfrastructureExceptionInterface;
use App\Exception\NotFoundExceptionInterface;
use App\Service\TextContentSerializer\TextContentSerializerServiceInterface;
use App\Tool\Mapper\GetPortfolioByTickerMapper;
use App\Tool\Mapper\GetPortfolioMapper;
use App\Tool\ToolInterface;
use Mcp\Types\CallToolResult;
use Mcp\Types\TextContent;
use Mcp\Types\Tool;
use Mcp\Types\ToolInputProperties;
use Mcp\Types\ToolInputSchema;
use Override;

final readonly class GetPortfolioTool implements ToolInterface
{
    private const string PARAMETER_TICKER = 'ticker';

    public function __construct(
        private string $accountId,
        private OperationsServiceComponentInterface $operationsServiceComponent,
        private GetPortfolioMapper $getPortfolioMapper,
        private GetPortfolioByTickerMapper $getPortfolioByTickerMapper,
        private TextContentSerializerServiceInterface $textContentSerializerService,
    ) {
    }

    #[Override]
    public function getName(): string
    {
        return ToolNameEnum::getPortfolio->value;
    }

    #[Override]
    public function getDescription(): string
    {
        return 'Возвращает информацию о текущем портфеле пользователя в Т-Инвестициях: '
            . 'тикер, количество, средняя и текущая цена, доходность, купонный доход (для облигаций). '
            . 'Портфель включает акции, облигации, фонды (ETF), валюту, фьючерсы, опционы и структурные продукты. ';
    }

    #[Override]
    public function getTool(): Tool
    {
        $properties = ToolInputProperties::fromArray([
            self::PARAMETER_TICKER => [
                'type' => 'string',
                'description' => 'Тикер инструмента'
            ],
        ]);

        $inputSchema = new ToolInputSchema(
            properties: $properties,
        );

        return new Tool(
            name: $this->getName(),
            inputSchema: $inputSchema,
            description: $this->getDescription()
        );
    }

    #[Override]
    public function __invoke(mixed ...$args): CallToolResult
    {
        $params = $args[0] ?? [];
        $ticker = $params[self::PARAMETER_TICKER] ?? null;

        try {
            $result = $this->operationsServiceComponent->getPortfolio(new GetPortfolioRequestDto(
                $this->accountId
            ));
        } catch (InfrastructureExceptionInterface $e) {
            return new CallToolResult(
                content: [new TextContent(text: "Unable to fetch data from T-Invest API: " . $e->getMessage())],
                isError: true
            );
        }

        if ($ticker !== null) {
            try {
                $content = $this->getPortfolioByTickerMapper->map($ticker, $result);
            } catch (NotFoundExceptionInterface $e) {
                return new CallToolResult(
                    content: [new TextContent(text: $e->getMessage())],
                    isError: true
                );
            }
        } else {
            $content = $this->getPortfolioMapper->map($result);
        }

        $text = $this->textContentSerializerService->serialize($content);

        return new CallToolResult(
            content: [new TextContent(
                text: $text,
            )]
        );
    }
}
