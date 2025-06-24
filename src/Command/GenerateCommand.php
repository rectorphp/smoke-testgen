<?php

declare(strict_types=1);

namespace Rector\SmokeTestgen\Command;

use Nette\Utils\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

final class GenerateCommand extends Command
{
    public function __construct(
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('generate');

        $this->setDescription('Generate Symfony smoke tests in "tests/unit/Smoke" namespace');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $symfonyStyle->writeln('<fg=green>Analyzing "composer.json" for existing framework</>');

        // load composer.json and replace versions in "require" and "require-dev",
        $composerJsonFilePath = getcwd() . '/composer.json';

        Assert::fileExists($composerJsonFilePath);
        $composerJsonContents = FileSystem::read($composerJsonFilePath);

        dump(123);

        $symfonyStyle->newLine();

        return self::SUCCESS;
    }
}
