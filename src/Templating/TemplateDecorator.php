<?php

declare(strict_types=1);

namespace Rector\SmokeTestgen\Templating;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;

final class TemplateDecorator
{
    public function decorate(string $templateContents, string $smokeTestsDirectory): string
    {
        $templateContents = $this->adjustKernelClass($templateContents);

        return $this->adjustNamespace($smokeTestsDirectory, $templateContents);
    }

    private function adjustKernelClass(string $templateContents): string
    {
        $kernelClass = $this->resolveKernelClass();

        return str_replace('__KERNEL_CLASS_PLACEHOLDER__', $kernelClass, $templateContents);
    }

    private function resolveKernelClass(): string
    {
        // use correct Kernel class
        if (class_exists('App\Kernel')) {
            return 'App\Kernel';
        }

        if (class_exists('AppKernel')) {
            return 'AppKernel';
        }

        return 'Kernel';
    }

    private function adjustNamespace(string $templateContents, string $smokeTestsDirectory): string
    {
        $projectTestsNamespace = $this->resolveProjectTestsNamespace();
        $smokeTestNamespace = $projectTestsNamespace . '\\Unit\\Smoke';

        return str_replace('__SMOKE_TEST_NAMESPACE__', $smokeTestNamespace, $templateContents);
    }

    private function resolveProjectTestsNamespace(): string
    {
        $composerJsonFilePath = getcwd() . '/composer.json';
        if (file_exists($composerJsonFilePath)) {
            $projectComposerJson = Json::decode(FileSystem::read($composerJsonFilePath), true);

            $autoloadDevPsr4 = $projectComposerJson['autoload-dev']['psr-4'] ?? [];
            foreach ($autoloadDevPsr4 as $namespace => $directory) {
                if ($directory === 'tests') {
                    return rtrim($namespace, '\\');
                }
            }

        }

        // fallback to default
        return 'App\Tests';
    }
}
