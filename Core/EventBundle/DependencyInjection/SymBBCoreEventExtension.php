<?php

namespace SymBB\Core\EventBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class SymBBCoreEventExtension extends Extension implements PrependExtensionInterface 
{
    
    public function prepend(ContainerBuilder $container)
    {
        
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
        
    public function load(array $configs, ContainerBuilder $container)
    {    
    }
}
