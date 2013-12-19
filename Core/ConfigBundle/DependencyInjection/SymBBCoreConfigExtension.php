<?php

namespace SymBB\Core\ConfigBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class SymBBCoreConfigExtension extends Extension implements PrependExtensionInterface 
{
    
    public function prepend(ContainerBuilder $container)
    {
        
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('doctrine.yml');
        $loader->load('twig.yml');
        $loader->load('symbb.yml');
        $loader->load('fos_user.yml');
        $loader->load('fos_rest.yml');
        $loader->load('knp.yml');
        $loader->load('fm_bbcode.yml');

    }
        
    public function load(array $configs, ContainerBuilder $container)
    {        
        
        $config = array();
        // reverse array
        $configs = array_reverse($configs);
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }
      
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, array($config));
        
        $container->setParameter('symbb_config', $config);
        $container->setParameter('twig.globals.symbb_config.template', $config['template']);
        
    }
}
