<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\ToolNameEnum;
use App\Factory\ToolFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-server',
    description: 'Тестовый запуск MCP сервера',
)]
class TestServerCommand extends Command
{
    public function __construct(
        private readonly ToolFactory $toolFactory,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('MCP Server Test');

        $fError = false;
        $fError |= !$this->callTool($io, ToolNameEnum::getAccounts);
        $fError |= !$this->callTool($io, ToolNameEnum::getPortfolio);

        if ($fError) {
            $io->error('One or more tools failed to execute.');
            return Command::FAILURE;
        }

        $io->success('Done.');
        return Command::SUCCESS;
    }

    private function callTool(SymfonyStyle $io, ToolNameEnum $name): bool
    {
        $io->info("Calling tool: {$name->name}");

        try {
            $tool = $this->toolFactory->create($name);
            $tool();
            return true;
        } catch (\Throwable $e) {
            //$io->error($e->getMessage());
            $io->error([
                'Exception' => $e::class,
                'Message'   => $e->getMessage(),
            ]);
            $io->writeln("<comment>Trace:</comment>\n" . $e->getTraceAsString());            return false;
        }
    }
}
