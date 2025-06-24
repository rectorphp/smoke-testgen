<?php

declare(strict_types=1);

namespace App\Tests\Unit\Smoke;

use AppKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractContainerTestCase extends TestCase
{
    protected static Container $container;

    protected static KernelInterface $kernel;

    protected function setUp(): void
    {
        // @todo configure your test environment: most likely "tests", "ci" or "dev"
        $appKernel = new AppKernel('tests', true);
        $appKernel->boot();

        self::$kernel = $appKernel;
        self::$container = $appKernel->getContainer()->get('test.service_container');
    }

    /**
     * @template TService as object
     *
     * @param class-string<TService>|string $type
     * @return ($type is "event_dispatcher" ? EventDispatcherInterface :
     *  ($type is "router" ? RouterInterface :
     *  ($type is "debug.controller_resolver" ? ControllerResolverInterface :
     *  ($type is "controller_resolver" ? ControllerResolverInterface : TService)
     * )))
     */
    protected function getService(string $type): object
    {
        /** @var Container $container */
        $container = self::$container;

        return $container->get($type);
    }
}
