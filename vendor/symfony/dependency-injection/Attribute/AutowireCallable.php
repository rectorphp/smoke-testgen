<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SmokeTestgen202507\Symfony\Component\DependencyInjection\Attribute;

use SmokeTestgen202507\Symfony\Component\DependencyInjection\Definition;
use SmokeTestgen202507\Symfony\Component\DependencyInjection\Exception\LogicException;
use SmokeTestgen202507\Symfony\Component\DependencyInjection\Reference;
/**
 * Attribute to tell which callable to give to an argument of type Closure.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
class AutowireCallable extends Autowire
{
    /**
     * @param bool|class-string $lazy Whether to use lazy-loading for this argument
     * @param string|mixed[]|null $callable
     */
    public function __construct($callable = null, ?string $service = null, ?string $method = null, $lazy = \false)
    {
        if (!(null !== $callable xor null !== $service)) {
            throw new LogicException('#[AutowireCallable] attribute must declare exactly one of $callable or $service.');
        }
        if (null === $service && null !== $method) {
            throw new LogicException('#[AutowireCallable] attribute cannot have a $method without a $service.');
        }
        parent::__construct($callable ?? [new Reference($service), $method ?? '__invoke'], null, null, null, null, $lazy);
    }
    /**
     * @param mixed $value
     */
    public function buildDefinition($value, ?string $type, \ReflectionParameter $parameter) : Definition
    {
        return (new Definition($type = \is_array($this->lazy) ? \current($this->lazy) : ($type ?: 'Closure')))->setFactory(['Closure', 'fromCallable'])->setArguments([\is_array($value) ? $value + [1 => '__invoke'] : $value])->setLazy($this->lazy || 'Closure' !== $type && 'callable' !== (string) $parameter->getType());
    }
}
