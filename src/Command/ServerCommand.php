<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\ToolNameEnum;
use App\Factory\ToolFactory;
use App\List\ToolsList;
use InvalidArgumentException;
use Mcp\Server\Server;
use Mcp\Server\ServerRunner;
use Mcp\Types\CallToolResult;
use Mcp\Types\ListToolsResult;
use Mcp\Types\TextContent;
use Override;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:mcp-server',
    description: 'Запуск MCP сервера',
)]
final class ServerCommand extends Command
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ToolsList $toolsList,
        private readonly ToolFactory $toolFactory,
    ) {
        parent::__construct();
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $server = new Server('t-invest-mcp-server', $this->logger);

        // Register tool handlers
        $server->registerHandler('tools/list', function (mixed $params) {
            $this->logger->info('tools/list', ['params' => $params]);
            $tools = [];
            foreach ($this->toolsList->get() as $toolItem) {
                $tools[] = $this->toolFactory->create($toolItem)->getTool();
            }

            return new ListToolsResult($tools);
        });

        $server->registerHandler('tools/call', function (mixed $params): CallToolResult {
            $this->logger->info('SERVER: tools/call - START', ['params' => $params]);
            try {
                $name = ToolNameEnum::tryFrom($params->name);
                if ($name === null) {
                    throw new InvalidArgumentException("Unknown tool: {$params->name}");
                }
                $tool = $this->toolFactory->create($name);
                $result = $tool->__invoke($params->arguments);
                $this->logger->info('SERVER: tools/call - DONE', ['params' => $params]);
                return $result;
            } catch (\Throwable $e) {
                $this->logger->error('SERVER: tools/call - ERROR', ['exception' => $e]);
                return new CallToolResult(
                    content: [new TextContent(text: $e->getMessage())],
                    isError: true,
                );
            }
        });

        // Create initialization options and run server
        $initOptions = $server->createInitializationOptions();
        $runner = new ServerRunner($server, $initOptions, $this->logger);
        $runner->run();

        return Command::SUCCESS;
    }
}
