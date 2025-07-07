<?php

declare(strict_types=1);

namespace App\Tool\GetAccounts;

use App\Component\UsersService\Dto\AccountDto as ComponentAccountDto;
use App\Component\UsersService\UsersServiceComponentInterface;
use App\Enum\ToolNameEnum;
use App\Exception\InfrastructureExceptionInterface;
use App\Tool\GetAccounts\Dto\AccountDto;
use App\Tool\ToolInterface;
use JsonException;
use Mcp\Types\CallToolResult;
use Mcp\Types\TextContent;
use Mcp\Types\Tool;
use Mcp\Types\ToolInputProperties;
use Mcp\Types\ToolInputSchema;
use Override;

final readonly class GetAccountsTool implements ToolInterface
{
    public function __construct(
        private UsersServiceComponentInterface $usersServiceComponent
    ) {
    }

    #[Override]
    public function getName(): string
    {
        return ToolNameEnum::getAccounts->value;
    }

    #[Override]
    public function getDescription(): string
    {
        return 'Возвращает список счетов пользователя в Т-Инвестициях';
    }

    #[Override]
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

    /**
     * @throws JsonException
     */
    #[Override]
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
                text: json_encode($content, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            )]
        );
    }
}
