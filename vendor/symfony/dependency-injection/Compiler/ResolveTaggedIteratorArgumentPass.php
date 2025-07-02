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

use SmokeTestgen202507\Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
/**
 * Resolves all TaggedIteratorArgument arguments.
 *
 * @author Roland Franssen <franssen.roland@gmail.com>
 */
class ResolveTaggedIteratorArgumentPass extends AbstractRecursivePass
{
    use PriorityTaggedServiceTrait;
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
        if (!$value instanceof TaggedIteratorArgument) {
            return parent::processValue($value, $isRoot);
        }
        $exclude = $value->getExclude();
        if ($value->excludeSelf()) {
            $exclude[] = $this->currentId;
        }
        $value->setValues($this->findAndSortTaggedServices($value, $this->container, $exclude));
        return $value;
    }
}
