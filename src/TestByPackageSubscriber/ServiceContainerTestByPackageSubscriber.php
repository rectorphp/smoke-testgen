<?php

namespace Rector\SmokeTestgen\TestByPackageSubscriber;

final class ServiceContainerTestByPackageSubscriber implements \Rector\SmokeTestgen\Contract\TestByPackageSubscriberInterface
{
    /**
     * @return string[]
     */
    public function getPackageNames(): array
    {
        return [
            'symfony/symfony',
            'symfony/dependency-injection',
        ];
    }

    public function getTestTemplateFilePath(): string
    {
        return __DIR__ . '/../../templates/Symfony';
    }
}
