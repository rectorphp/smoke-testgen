<?php

declare(strict_types=1);

namespace Rector\SmokeTestgen\Utils;

use Rector\SmokeTestgen\Contract\TestByPackageSubscriberInterface;
use Webmozart\Assert\Assert;

final class TestPathResolver
{
    public static function resolve(
        TestByPackageSubscriberInterface $testByPackageSubscriber,
        string $smokeTestsDirectory
    ): string {
        Assert::fileExists($testByPackageSubscriber->getTemplateFilePath());

        $absolutePath = $testByPackageSubscriber->getTemplateFilePath();
        $testFileBasename = pathinfo($absolutePath, PATHINFO_BASENAME);

        return $smokeTestsDirectory . '/' . $testFileBasename;
    }
}
