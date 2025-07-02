<?php

declare(strict_types=1);

namespace Rector\SmokeTestgen\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Rector\SmokeTestgen\TestByPackageSubscriber\ServiceContainerTestByPackageSubscriber;
use Rector\SmokeTestgen\Utils\TestPathResolver;

/**
 * @see \Rector\SmokeTestgen\Utils\TestPathResolver
 */
final class TestPathResolverTest extends TestCase
{
    public function test(): void
    {
        $serviceContainerTestByPackageSubscriber = new ServiceContainerTestByPackageSubscriber();
        $testPath = TestPathResolver::resolve($serviceContainerTestByPackageSubscriber, 'some-path');

        $this->assertSame('some-path/ServiceContainerTest.php', $testPath);
    }
}
