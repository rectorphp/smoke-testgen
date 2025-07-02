<?php

declare (strict_types=1);
namespace Rector\SmokeTestgen\Console;

use Rector\SmokeTestgen\Console\Command\CleanListCommand;
use SmokeTestgen202507\Symfony\Component\Console\Application;
use SmokeTestgen202507\Symfony\Component\Console\Command\CompleteCommand;
use SmokeTestgen202507\Symfony\Component\Console\Command\DumpCompletionCommand;
use SmokeTestgen202507\Symfony\Component\Console\Command\HelpCommand;
final class SmokeTestgenConsoleApplication extends Application
{
    protected function getDefaultCommands() : array
    {
        return [
            new HelpCommand(),
            new CompleteCommand(),
            new DumpCompletionCommand(),
            // clean list, without bloated options
            new CleanListCommand(),
        ];
    }
}
