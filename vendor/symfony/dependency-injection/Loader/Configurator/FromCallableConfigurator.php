<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SmokeTestgen202507\Symfony\Component\DependencyInjection\Loader\Configurator;

use SmokeTestgen202507\Symfony\Component\DependencyInjection\Definition;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class FromCallableConfigurator extends AbstractServiceConfigurator
{
    use Traits\AbstractTrait;
    use Traits\AutoconfigureTrait;
    use Traits\AutowireTrait;
    use Traits\BindTrait;
    use Traits\DecorateTrait;
    use Traits\DeprecateTrait;
    use Traits\LazyTrait;
    use Traits\PublicTrait;
    use Traits\ShareTrait;
    use Traits\TagTrait;
    public const FACTORY = 'services';
    /**
     * @var \Symfony\Component\DependencyInjection\Loader\Configurator\ServiceConfigurator
     */
    private $serviceConfigurator;
    public function __construct(ServiceConfigurator $serviceConfigurator, Definition $definition)
    {
        $this->serviceConfigurator = $serviceConfigurator;
        parent::__construct($serviceConfigurator->parent, $definition, $serviceConfigurator->id);
    }
    public function __destruct()
    {
        $this->serviceConfigurator->__destruct();
    }
}
