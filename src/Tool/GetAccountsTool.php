<?php

namespace App\Tool;

use App\Component\UsersService\Dto\AccountDto as ComponentAccountDto;
use App\Component\UsersService\UsersServiceComponentInterface;
use App\Enum\ToolNameEnum;
use App\Exception\InfrastructureExceptionInterface;
use App\Tool\Dto\AccountDto;
use Mcp\Types\CallToolResult;
use Mcp\Types\TextContent;
use Mcp\Types\Tool;
use Mcp\Types\ToolInputProperties;
use Mcp\Types\ToolInputSchema;

readonly class GetAccountsTool implements ToolInterface
{
    public function __construct(
        private UsersServiceComponentInterface $usersServiceComponent
    ) {
    }

    public function getName(): string
    {
        return ToolNameEnum::getAccounts->value;
    }

    public function getDescription(): string
    {
        return 'Возвращает список счетов пользователя в Т-Инвестициях';
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
            $result = $this->usersServiceComponent->getAccounts();
        } catch (InfrastructureExceptionInterface $e) {
            return new CallToolResult(
                content: [new TextContent(text: "Unable to fetch accounts from T-Invest API: " . $e->getMessage())],
                isError: true
            );
        }

        $content = array_map(function (ComponentAccountDto $item) {
            return new AccountDto(
                $item->id,
                $item->name,
                $item->accessLevel,
            );
        }, $result->accounts);

        return new CallToolResult(
            content: [new TextContent(
                text: json_encode($content, JSON_UNESCAPED_UNICODE),
            )]
        );
    }
}
