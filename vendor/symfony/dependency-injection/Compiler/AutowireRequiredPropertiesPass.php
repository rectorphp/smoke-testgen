<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SmokeTestgen202507\Symfony\Component\DependencyInjection\Compiler;

use SmokeTestgen202507\Symfony\Component\DependencyInjection\ContainerInterface;
use SmokeTestgen202507\Symfony\Component\DependencyInjection\Definition;
use SmokeTestgen202507\Symfony\Component\DependencyInjection\TypedReference;
use SmokeTestgen202507\Symfony\Contracts\Service\Attribute\Required;
/**
 * Looks for definitions with autowiring enabled and registers their corresponding "#[Required]" properties.
 *
 * @author Sebastien Morel (Plopix) <morel.seb@gmail.com>
 * @author Nicolas Grekas <p@tchwork.com>
 */
class AutowireRequiredPropertiesPass extends AbstractRecursivePass
{
    /**
     * @var bool
     */
    protected $skipScalars = \true;
    /**
     * @param mixed $value
     * @return mixed
     */
    protected function processValue($value, bool $isRoot = \false)
    {
        $value = parent::processValue($value, $isRoot);
        if (!$value instanceof Definition || !$value->isAutowired() || $value->isAbstract() || !$value->getClass()) {
            return $value;
        }
        if (!($reflectionClass = $this->container->getReflectionClass($value->getClass(), \false))) {
            return $value;
        }
        $properties = $value->getProperties();
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if (!($type = \method_exists($reflectionProperty, 'getType') ? $reflectionProperty->getType() : null) instanceof \ReflectionNamedType) {
                continue;
            }
            $doc = \false;
            if (!(\method_exists($reflectionProperty, 'getAttributes') ? $reflectionProperty->getAttributes(Required::class) : []) && (\false === ($doc = $reflectionProperty->getDocComment()) || \false === \stripos($doc, '@required') || !\preg_match('#(?:^/\\*\\*|\\n\\s*+\\*)\\s*+@required(?:\\s|\\*/$)#i', $doc))) {
                continue;
            }
            if ($doc) {
                trigger_deprecation('symfony/dependency-injection', '6.3', 'Using the "@required" annotation on property "%s::$%s" is deprecated, use the "Symfony\\Contracts\\Service\\Attribute\\Required" attribute instead.', $reflectionProperty->class, $reflectionProperty->name);
            }
            if (\array_key_exists($name = $reflectionProperty->getName(), $properties)) {
                continue;
            }
            $type = $type->getName();
            $value->setProperty($name, new TypedReference($type, $type, ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $name));
        }
        return $value;
    }
}
