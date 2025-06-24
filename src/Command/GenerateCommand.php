<?php

declare(strict_types=1);

namespace Rector\SmokeTestgen\Command;

use Rector\SmokeTestgen\FIleSystem\TestsDirectoryResolver;
use Rector\SmokeTestgen\TestTemplateResolver;
use Rector\SmokeTestgen\Utils\JsonFileLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class GenerateCommand extends Command
{
    public function __construct(
        private readonly TestsDirectoryResolver $testsDirectoryResolver,
        private readonly TestTemplateResolver $testTemplateResolver
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

        $requirePackages = $this->resolveProjectRequiredPackageNames(getcwd());

        $testByPackageSubscribers = $this->testTemplateResolver->matchProjectPackages($requirePackages);

        if ($testByPackageSubscribers === []) {
            $symfonyStyle->warning('No test templates found for the required packages. Make sure you project uses Composer to manage version and has Symfony/Doctrine packages listed in "require" section');
            return self::FAILURE;
        }


        $symfonyStyle->newLine();
        $symfonyStyle->writeln(sprintf(
            'Found <fg=yellow>%d smoke test%s</>, that might come handy',
            count($testByPackageSubscribers),
            count($testByPackageSubscribers) > 1 ? 's' : ''
        ));

        foreach ($testByPackageSubscribers as $testByPackageSubscriber) {

        }

        $symfonyStyle->newLine();

        return self::SUCCESS;
    }

    /**
     * @return string[]
     */
    private function resolveProjectRequiredPackageNames(string $projectDirectory): array
    {
        // load composer.json and replace versions in "require" and "require-dev",
        $composerJson = JsonFileLoader::loadFileToJson($projectDirectory . '/composer.json');

        // has symfony/symfony or symfony/dependency-injection
        $requirePackagesToVersions = $composerJson['require'] ?? [];

        return array_keys($requirePackagesToVersions);
    }
}
