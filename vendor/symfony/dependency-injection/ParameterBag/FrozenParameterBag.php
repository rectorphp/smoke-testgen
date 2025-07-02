<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SmokeTestgen202507\Symfony\Component\DependencyInjection\ParameterBag;

use SmokeTestgen202507\Symfony\Component\DependencyInjection\Exception\LogicException;
/**
 * Holds read-only parameters.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class FrozenParameterBag extends ParameterBag
{
    /**
     * @var mixed[]
     */
    protected $deprecatedParameters = [];
    /**
     * For performance reasons, the constructor assumes that
     * all keys are already lowercased.
     *
     * This is always the case when used internally.
     */
    public function __construct(array $parameters = [], array $deprecatedParameters = [])
    {
        $this->deprecatedParameters = $deprecatedParameters;
        $this->parameters = $parameters;
        $this->resolved = \true;
    }
    /**
     * @return never
     */
    public function clear()
    {
        throw new LogicException('Impossible to call clear() on a frozen ParameterBag.');
    }
    /**
     * @return never
     */
    public function add(array $parameters)
    {
        throw new LogicException('Impossible to call add() on a frozen ParameterBag.');
    }
    /**
     * @return never
     * @param mixed[]|bool|string|int|float|\UnitEnum|null $value
     */
    public function set(string $name, $value)
    {
        throw new LogicException('Impossible to call set() on a frozen ParameterBag.');
    }
    /**
     * @return never
     */
    public function deprecate(string $name, string $package, string $version, string $message = 'The parameter "%s" is deprecated.')
    {
        throw new LogicException('Impossible to call deprecate() on a frozen ParameterBag.');
    }
    /**
     * @return never
     */
    public function remove(string $name)
    {
        throw new LogicException('Impossible to call remove() on a frozen ParameterBag.');
    }
}
