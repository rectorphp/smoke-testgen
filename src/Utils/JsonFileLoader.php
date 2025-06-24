<?php

declare(strict_types=1);

namespace Rector\SmokeTestgen\Utils;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Webmozart\Assert\Assert;

final class JsonFileLoader
{
    /**
     * @return array<string, mixed>
     */
    public static function loadFileToJson(string $filePath): array
    {
        Assert::fileExists($filePath);

        $fileContents = FileSystem::read($filePath);

        return Json::decode($fileContents, true);
    }
}
