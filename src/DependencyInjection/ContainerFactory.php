<?php

declare(strict_types=1);

namespace Rector\SmokeTestgen\DependencyInjection;

use Illuminate\Container\Container;
use Rector\SmokeTestgen\Console\SmokeTestgenConsoleApplication;
use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;
use Webmozart\Assert\Assert;

final class ContainerFactory
{
    /**
     * @api used in bin and tests
     */
    public function create(): Container
    {
        $container = new Container();

        // console
        $container->singleton(Application::class, function (Container $container): Application {
            $jackConsoleApplication = new SmokeTestgenConsoleApplication('Rector Smoke Testgen');

            $commandClasses = $this->findCommandClasses();

            // register commands
            foreach ($commandClasses as $commandClass) {
                $command = $container->make($commandClass);
                $jackConsoleApplication->add($command);
            }

            // remove basic command to make output clear
            $this->hideDefaultCommands($jackConsoleApplication);

            return $jackConsoleApplication;
        });

        return $container;
    }

    public function hideDefaultCommands(Application $application): void
    {
        $application->get('list')
            ->setHidden(true);
        $application->get('completion')
            ->setHidden(true);
        $application->get('help')
            ->setHidden(true);
    }

    /**
     * @return string[]
     */
    private function findCommandClasses(): array
    {
        $commandFinder = Finder::create()
            ->files()
            ->name('*Command.php')
            ->in(__DIR__ . '/../Command');

        $commandClasses = [];
        foreach ($commandFinder as $commandFile) {
            $commandClass = 'Rector\\SmokeTestgen\\Command\\' . $commandFile->getBasename('.php');

            // make sure it exists
            Assert::classExists($commandClass);

            $commandClasses[] = $commandClass;
        }

        return $commandClasses;
    }
}
