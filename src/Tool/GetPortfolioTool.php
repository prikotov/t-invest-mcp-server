<?php

namespace App\Tool;

use App\Component\OperationsService\Dto\GetPortfolioRequestDto;
use App\Component\OperationsService\OperationsServiceComponentInterface;
use App\Enum\ToolNameEnum;
use App\Exception\InfrastructureExceptionInterface;
use App\Tool\Mapper\GetPortfolioMapper;
use Mcp\Types\CallToolResult;
use Mcp\Types\TextContent;
use Mcp\Types\Tool;
use Mcp\Types\ToolInputProperties;
use Mcp\Types\ToolInputSchema;

readonly class GetPortfolioTool implements ToolInterface
{
    public function __construct(
        private string $accountId,
        private OperationsServiceComponentInterface $operationsServiceComponent,
        private GetPortfolioMapper $getPortfolioMapper,
    ) {
    }

    public function getName(): string
    {
        return ToolNameEnum::getPortfolio->value;
    }

    public function getDescription(): string
    {
        return 'Получить портфель.';
    }

    public function getTool(): Tool
    {
        $properties = ToolInputProperties::fromArray([
        ]);

        $inputSchema = new ToolInputSchema(
            properties: $properties,
            required: null
        );

        return new Tool(
            name: $this->getName(),
            inputSchema: $inputSchema,
            description: $this->getDescription()
        );
    }

    public function __invoke(mixed ...$args): CallToolResult
    {
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

        // todo маппинг структуры в понятную схему
        $obj = $this->getPortfolioMapper->map($result);

        return new CallToolResult(
            content: [new TextContent(
                text: json_encode($obj, JSON_UNESCAPED_UNICODE),
            )]
        );
    }
}
