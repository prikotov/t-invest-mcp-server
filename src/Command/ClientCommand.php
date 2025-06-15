<?php

declare(strict_types=1);

namespace App\Command;

use Mcp\Client\Client;
use Mcp\Client\Transport\EnvironmentHelper;
use Mcp\Client\Transport\StdioServerParameters;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:mcp-client',
    description: 'Протестировать MCP',
)]
class ClientCommand extends Command
{
    public function __construct(
        private LoggerInterface $logger,
        private readonly string $token,
        private readonly string $baseUrl,
        private readonly string $accountId,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'via',
            null,
            InputOption::VALUE_REQUIRED,
            'Launch server via: console, podman, docker',
            'console'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $via = $input->getOption('via');
        switch ($via) {
            case 'podman':
                EnvironmentHelper::initialize();
                $serverParams = new StdioServerParameters(
                    command: 'podman',
                    args: [
                        'run',
                        '--rm',
                        '-i',
                        '-e',
                        'APP_T_INVEST_BASE_URL',
                        '-e',
                        'APP_T_INVEST_TOKEN',
                        '-e',
                        'APP_T_INVEST_ACCOUNT_ID',
                        't-invest-mcp-server',
                        'bin/server',
                    ],
                    env: array_merge(EnvironmentHelper::getDefaultEnvironment(), [
                        'APP_T_INVEST_BASE_URL' => $this->baseUrl,
                        'APP_T_INVEST_TOKEN' => $this->token,
                        'APP_T_INVEST_ACCOUNT_ID' => $this->accountId,
                    ])
                );
                break;
            case 'docker':
                EnvironmentHelper::initialize();
                $serverParams = new StdioServerParameters(
                    command: 'docker',
                    args: [
                        'run',
                        '--rm',
                        '-i',
                        '-e',
                        'APP_T_INVEST_BASE_URL',
                        '-e',
                        'APP_T_INVEST_TOKEN',
                        '-e',
                        'APP_T_INVEST_ACCOUNT_ID',
                        't-invest-mcp-server',
                        'bin/server',
                    ],
                    env: array_merge(EnvironmentHelper::getDefaultEnvironment(), [
                        'APP_T_INVEST_BASE_URL' => $this->baseUrl,
                        'APP_T_INVEST_TOKEN' => $this->token,
                        'APP_T_INVEST_ACCOUNT_ID' => $this->accountId,
                    ])
                );
                break;
            case 'console':
            default:
                $serverParams = new StdioServerParameters(
                    command: 'bin/server',
                );
        }

        $io->title('MCP Client Test');

        // Create client instance
        $client = new Client($this->logger);

        try {
            $io->info('Connecting to MCP server...');
            // Connect to the server using stdio transport
            $session = $client->connect(
                commandOrUrl: $serverParams->getCommand(),
                args: $serverParams->getArgs(),
                env: $serverParams->getEnv(),
            );

            $io->info('Fetching list of available tools...');
            $toolsResult = $session->listTools();
            if (!empty($toolsResult->tools)) {
                $io->info('Available tools:');
                foreach ($toolsResult->tools as $tool) {
                    $output->writeln('  - Name: ' . $tool->name);
                    $output->writeln('    Description: ' . $tool->description);
                    $output->writeln('    Arguments:');
                    $output->writeln('      - ' . json_encode($tool->inputSchema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                }
            } else {
                $io->warning('No tools available.');
            }

            $io->info('Calling tool: get_accounts');
            $toolsResult = $session->callTool('get_accounts');
            $output->writeln(json_encode(json_decode($toolsResult->content[0]->text, true), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

            $io->info('Calling tool: get_portfolio');
            $toolsResult = $session->callTool('get_portfolio');
            $output->writeln(json_encode(json_decode($toolsResult->content[0]->text, true), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            $io->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        } finally {
            // Close the server connection
            $client->close();
            $io->info('Close the server connection.');
        }

        $io->success('Done.');

        return Command::SUCCESS;
    }
}
