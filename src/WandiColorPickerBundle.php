<?php

namespace Wandi\ColorPickerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wandi\ColorPickerBundle\DependencyInjection\WandiColorPickerExtension;

/**
 * Class WandiColorPickerBundle
 *
 * @author Laurent Bientz <laurent@wandi.fr>
 * @package Wandi\ColorPickerBundle
 */
class WandiColorPickerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensionClass(): string
    {
        return WandiColorPickerExtension::class;
    }
}
