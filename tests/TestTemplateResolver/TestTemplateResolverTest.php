<?php

declare(strict_types=1);

namespace Rector\SmokeTestgen\Tests\TestTemplateResolver;

use Rector\SmokeTestgen\Tests\AbstractTestCase;
use Rector\SmokeTestgen\TestTemplateResolver;

/**
 * @see TestTemplateResolver
 */
final class TestTemplateResolverTest extends AbstractTestCase
{
    public function test(): void
    {
        $testTemplateResolver = $this->make(TestTemplateResolver::class);

        $testByPackageSubscribers = $testTemplateResolver->matchProjectPackages(['symfony/dependency-injection']);

        $this->assertCount(1, $testByPackageSubscribers);
    }
}
