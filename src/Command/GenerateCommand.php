<?php

declare (strict_types=1);
namespace Rector\SmokeTestgen\Command;

use SmokeTestgen202507\Nette\Utils\FileSystem;
use Rector\SmokeTestgen\Contract\TestByPackageSubscriberInterface;
use Rector\SmokeTestgen\FIleSystem\TestsDirectoryResolver;
use Rector\SmokeTestgen\TestTemplateResolver;
use Rector\SmokeTestgen\Utils\JsonFileLoader;
use SmokeTestgen202507\Symfony\Component\Console\Command\Command;
use SmokeTestgen202507\Symfony\Component\Console\Input\InputInterface;
use SmokeTestgen202507\Symfony\Component\Console\Output\OutputInterface;
use SmokeTestgen202507\Symfony\Component\Console\Style\SymfonyStyle;
use SmokeTestgen202507\Webmozart\Assert\Assert;
final class GenerateCommand extends Command
{
    /**
     * @readonly
     * @var \Rector\SmokeTestgen\FIleSystem\TestsDirectoryResolver
     */
    private $testsDirectoryResolver;
    /**
     * @readonly
     * @var \Rector\SmokeTestgen\TestTemplateResolver
     */
    private $testTemplateResolver;
    public function __construct(TestsDirectoryResolver $testsDirectoryResolver, TestTemplateResolver $testTemplateResolver)
    {
        $this->testsDirectoryResolver = $testsDirectoryResolver;
        $this->testTemplateResolver = $testTemplateResolver;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setName('generate');
        $this->setDescription('Generate Symfony smoke tests in "tests/unit/Smoke" namespace');
    }
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->writeln('<fg=green>Resolving directory for smoke tests</>');
        $smokeTestsDirectory = $this->testsDirectoryResolver->resolveSmokeUnitTestDirectory(\getcwd());
        $symfonyStyle->writeln(' * ' . $smokeTestsDirectory);
        $requirePackages = $this->resolveProjectRequiredPackageNames(\getcwd());
        $testByPackageSubscribers = $this->testTemplateResolver->matchProjectPackages($requirePackages);
        if ($testByPackageSubscribers === []) {
            $symfonyStyle->warning('No test templates found for the required packages. Make sure you project uses Composer to manage version and has Symfony/Doctrine packages listed in "require" section');
            return self::FAILURE;
        }
        $symfonyStyle->newLine();
        $symfonyStyle->writeln(\sprintf('Found <fg=yellow>%d smoke test%s</>, that might come handy', \count($testByPackageSubscribers), \count($testByPackageSubscribers) > 1 ? 's' : ''));
        $generatedTestCount = 0;
        foreach ($testByPackageSubscribers as $testByPackageSubscriber) {
            $projectTestFilePath = $this->resolveProjectTestFilePath($testByPackageSubscriber, $smokeTestsDirectory);
            if (\file_exists($projectTestFilePath)) {
                $symfonyStyle->writeln(\sprintf('File <fg=green>%s</> already exists, skipping', $projectTestFilePath));
                continue;
            }
            $templateContents = FileSystem::read($testByPackageSubscriber->getTemplateFilePath());
            $templateContents = $this->addjustTestFileNamespace($templateContents, $smokeTestsDirectory);
            FileSystem::write($templateContents, $projectTestFilePath);
            $symfonyStyle->writeln(\sprintf('Generated new test file %s', $projectTestFilePath));
            ++$generatedTestCount;
        }
        if ($generatedTestCount === 0) {
            $symfonyStyle->success('No new test files were generated. All required tests already exist.');
            return self::SUCCESS;
        }
        // make sure the abstract test case is always present
        $projectTestCaseFilePath = $smokeTestsDirectory . '/AbstractContainerTestCase.php';
        if (!\file_exists($projectTestCaseFilePath)) {
            $templateContents = FileSystem::read(__DIR__ . '/../../templates/Symfony/AbstractContainerTestCase.php');
            FileSystem::write($templateContents, $projectTestFilePath);
        }
        $symfonyStyle->success(\sprintf('Generated %d new test file%s in "%s"', $generatedTestCount, $generatedTestCount > 1 ? 's' : '', $smokeTestsDirectory));
        $symfonyStyle->newLine();
        return self::SUCCESS;
    }
    /**
     * @return string[]
     */
    private function resolveProjectRequiredPackageNames(string $projectDirectory) : array
    {
        // load composer.json and replace versions in "require" and "require-dev",
        $composerJson = JsonFileLoader::loadFileToJson($projectDirectory . '/composer.json');
        // has symfony/symfony or symfony/dependency-injection
        $requirePackagesToVersions = $composerJson['require'] ?? [];
        $packageNames = \array_keys($requirePackagesToVersions);
        Assert::allString($packageNames);
        return $packageNames;
    }
    private function resolveProjectTestFilePath(TestByPackageSubscriberInterface $testByPackageSubscriber, string $smokeTestsDirectory) : string
    {
        Assert::fileExists($testByPackageSubscriber->getTemplateFilePath());
        $absolutePath = $testByPackageSubscriber->getTemplateFilePath();
        $testFileBasename = \pathinfo($absolutePath, \PATHINFO_BASENAME);
        return $smokeTestsDirectory . '/' . $testFileBasename;
    }
    private function addjustTestFileNamespace(string $templateContents, string $smokeTestsDirectory) : string
    {
        if ($smokeTestsDirectory === 'tests/Unit/Smoke') {
            // default one, nothing to adjust
            return $templateContents;
        }
        $namespace = \str_replace('/', '\\', $smokeTestsDirectory);
        $namespace = \lcfirst($namespace);
        return \str_replace('namespace App\\Tests\\Unit\\Smoke', 'namespace App\\' . $namespace, $templateContents);
    }
}
