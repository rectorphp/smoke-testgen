<?php

declare(strict_types=1);

namespace Rector\SmokeTestgen\Templating;

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
        if ($smokeTestsDirectory === 'tests/Unit/Smoke') {
            // default one, nothing to adjust
            return $templateContents;
        }

        // @todo check with composer.json "psr-4" in require-dev
        $namespace = str_replace('/', '\\', $smokeTestsDirectory);
        $namespace = lcfirst($namespace);

        return str_replace('namespace App\Tests\Unit\Smoke', 'namespace App\\' . $namespace, $templateContents);
    }
}
