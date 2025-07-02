<?php

declare (strict_types=1);
namespace Rector\SmokeTestgen\Utils;

use SmokeTestgen202507\Nette\Utils\FileSystem;
use SmokeTestgen202507\Nette\Utils\Json;
use SmokeTestgen202507\Webmozart\Assert\Assert;
final class JsonFileLoader
{
    /**
     * @return array<string, mixed>
     */
    public static function loadFileToJson(string $filePath) : array
    {
        Assert::fileExists($filePath);
        $fileContents = FileSystem::read($filePath);
        return Json::decode($fileContents, \true);
    }
}
