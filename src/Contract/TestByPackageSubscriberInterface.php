<?php

declare(strict_types=1);

namespace Rector\SmokeTestgen\Contract;

interface TestByPackageSubscriberInterface
{
    /**
     * @return string[]
     */
    public function getPackageNames(): array;

    public function getTemplateFilePath(): string;
}
