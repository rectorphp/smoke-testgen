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

use SmokeTestgen202507\Symfony\Component\DependencyInjection\Argument\ArgumentInterface;
use SmokeTestgen202507\Symfony\Component\DependencyInjection\Exception\LogicException;
use SmokeTestgen202507\Symfony\Component\DependencyInjection\Reference;
use SmokeTestgen202507\Symfony\Component\ExpressionLanguage\Expression;
/**
 * Attribute to tell a parameter how to be autowired.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
class Autowire
{
    /**
     * @readonly
     * @var string|mixed[]|\Symfony\Component\ExpressionLanguage\Expression|\Symfony\Component\DependencyInjection\Reference|\Symfony\Component\DependencyInjection\Argument\ArgumentInterface|null
     */
    public $value;
    /**
     * @readonly
     * @var mixed[]|bool
     */
    public $lazy;
    /**
     * Use only ONE of the following.
     *
     * @param string|array|ArgumentInterface|null $value      Value to inject (ie "%kernel.project_dir%/some/path")
     * @param string|null                         $service    Service ID (ie "some.service")
     * @param string|null                         $expression Expression (ie 'service("some.service").someMethod()')
     * @param string|null                         $env        Environment variable name (ie 'SOME_ENV_VARIABLE')
     * @param string|null                         $param      Parameter name (ie 'some.parameter.name')
     * @param bool|class-string|class-string[]    $lazy       Whether to use lazy-loading for this argument
     */
    public function __construct($value = null, ?string $service = null, ?string $expression = null, ?string $env = null, ?string $param = null, $lazy = \false)
    {
        if ($this->lazy = \is_string($lazy) ? [$lazy] : $lazy) {
            if (null !== ($expression ?? $env ?? $param)) {
                throw new LogicException('#[Autowire] attribute cannot be $lazy and use $expression, $env, or $param.');
            }
            if (null !== $value && null !== $service) {
                throw new LogicException('#[Autowire] attribute cannot declare $value and $service at the same time.');
            }
        } elseif (!(null !== $value xor null !== $service xor null !== $expression xor null !== $env xor null !== $param)) {
            throw new LogicException('#[Autowire] attribute must declare exactly one of $service, $expression, $env, $param or $value.');
        }
        if (\is_string($value) && \strncmp($value, '@', \strlen('@')) === 0) {
            switch (\true) {
                case \strncmp($value, '@@', \strlen('@@')) === 0:
                    $value = \substr($value, 1);
                    break;
                case \strncmp($value, '@=', \strlen('@=')) === 0:
                    $expression = \substr($value, 2);
                    break;
                default:
                    $service = \substr($value, 1);
                    break;
            }
        }
        switch (\true) {
            case null !== $service:
                $this->value = new Reference($service);
                break;
            case null !== $expression:
                if (!\class_exists(Expression::class)) {
                    throw new LogicException('Unable to use expressions as the Symfony ExpressionLanguage component is not installed. Try running "composer require symfony/expression-language".');
                }
                $this->value = new Expression($expression);
                break;
            case null !== $env:
                $this->value = "%env({$env})%";
                break;
            case null !== $param:
                $this->value = "%{$param}%";
                break;
            default:
                $this->value = $value;
                break;
        }
    }
}
