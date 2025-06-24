<?php

declare(strict_types=1);

namespace Rector\SmokeTestgen;

use Rector\SmokeTestgen\Contract\TestByPackageSubscriberInterface;
use Rector\SmokeTestgen\TestByPackageSubscriber\ServiceContainerTestByPackageSubscriber;
use Webmozart\Assert\Assert;

final class TestTemplateResolver
{
    /**
     * @var TestByPackageSubscriberInterface[]
     */
    private array $testByPackageSubscribers;

    public function __construct(ServiceContainerTestByPackageSubscriber $serviceContainerTestByPackageSubscriber)
    {
        $this->testByPackageSubscribers[] = $serviceContainerTestByPackageSubscriber;
    }

    /**
     * @param string[] $requiredPackages
     * @return TestByPackageSubscriberInterface[]
     */
    public function matchProjectPackages(array $requiredPackages): array
    {
        Assert::allString($requiredPackages);

        // find all subscribers, that match any of the required packages
        $matchedSubscribers = [];
        foreach ($this->testByPackageSubscribers as $testByPackageSubscriber) {
            if (array_intersect($testByPackageSubscriber->getPackageNames(), $requiredPackages) === []) {
                continue;
            }

            $matchedSubscribers[] = $testByPackageSubscriber;
        }

        return $matchedSubscribers;
    }
}
