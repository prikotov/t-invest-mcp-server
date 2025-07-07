<?php

declare(strict_types=1);

namespace App\Command;

use Override;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:test')]
final class TestCommand extends Command
{
    public function __construct(
        private readonly LoggerInterface $logger,
        ?string $name = null,
    ) {
        parent::__construct(name: $name);
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info("Run test command");
        $output->writeln('ok');
        $this->logger->info("Done test command");
        return Command::SUCCESS;
    }
}
