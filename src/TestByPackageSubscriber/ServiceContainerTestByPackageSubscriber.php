<?php

namespace Rector\SmokeTestgen\TestByPackageSubscriber;

use Rector\SmokeTestgen\Contract\TestByPackageSubscriberInterface;

final class ServiceContainerTestByPackageSubscriber implements TestByPackageSubscriberInterface
{
    /**
     * @return string[]
     */
    public function getPackageNames(): array
    {
        return ['symfony/symfony', 'symfony/dependency-injection'];
    }

    public function getTemplateFilePath(): string
    {
        return __DIR__ . '/../../templates/Symfony/ServiceContainerTest.php';
    }
}
