<?php

declare(strict_types=1);

namespace Rector\SmokeTestgen\Command;

use Nette\Utils\FileSystem;
use Rector\SmokeTestgen\FIleSystem\TestsDirectoryResolver;
use Rector\SmokeTestgen\Utils\JsonFileLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Webmozart\Assert\Assert;

final class GenerateCommand extends Command
{
    public function __construct(
        private TestsDirectoryResolver $testsDirectoryResolver,
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
        $symfonyStyle->writeln('<fg=green>Resolving directory for smoke tests</>');

        $smokeTestsDirectory = $this->testsDirectoryResolver->resolveSmokeUnitTestDirectory(getcwd());
        $symfonyStyle->writeln(' * ' . $smokeTestsDirectory);

        // load composer.json and replace versions in "require" and "require-dev",
        $composerJson = JsonFileLoader::loadFileToJson(getcwd() . '/composer.json');

        // has symfony/symfony or symfony/dependency-injection
        $requirePackages = $composerJson['require'] ?? [];



        dump($requirePackages);
        die;

        $symfonyStyle->newLine();

        return self::SUCCESS;
    }

//    private function resolveUnitTestsDirectory(string $testDirectory): ?string
//    {
//        // find test directory
//        $unitTestDirectoryFinder = Finder::create()
//            ->directories()
//            ->name('#unit#i')
//            ->in($testDirectory)
//            ->depth(0)
//            ->getIterator();
//
//        foreach ($unitTestDirectoryFinder as $unitTestDirectory) {
//            return $unitTestDirectory->getRelativePathname();
//        }
//
//        return null;
//    }
//
//    private function resolveTestDirectory(): ?string
//    {
//        // find test directory
//        $testDirectoryFinder = Finder::create()
//            ->directories()
//            ->name('#test#i')
//            ->in(getcwd())
//            ->depth(0)
//            ->getIterator();
//
//        foreach ($testDirectoryFinder as $testDirectory) {
//            return $testDirectory->getRelativePathname();
//        }
//
//        return null;
//    }
//
//    private function resolveSmokeUnitTestDirectory(): string
//    {
//        $testDirectory = $this->resolveTestDirectory();
//
//        if ($testDirectory === null) {
//            // fallback to default
//            $testDirectory = 'tests';
//        }
//
//        $unitTestDirectory = $this->resolveUnitTestsDirectory($testDirectory);
//        if ($unitTestDirectory === null) {
//            // fallback to "Unit"
//            return $testDirectory . '/Unit/Smoke';
//
//        }
//
//        return $testDirectory . '/' . $unitTestDirectory . '/Smoke';
//    }
}
