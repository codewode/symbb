<?
namespace SymBB\Core\ConfigBundle\DependencyInjection; 

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface 
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('symbb_config');

        $rootNode
            ->children()
                ->arrayNode('database') 
                    ->scalarNode('table_prefix') 
                        ->defaultValue('symbb_')
                    ->end()
                ->end()
                ->arrayNode('template') 
                    ->scalarNode('acp') 
                        ->defaultValue('SymBBTemplateAcpBundle')
                    ->end()
                    ->scalarNode('forum') 
                        ->defaultValue('SymBBTemplateSimpleBundle')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}