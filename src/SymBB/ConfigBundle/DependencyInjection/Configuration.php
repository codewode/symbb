<?
namespace SymBB\ConfigBundle\DependencyInjection; 

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
                ->scalarNode('table_prefix') 
                    ->defaultValue('symbb_')
                ->end()
            ->end();

        return $treeBuilder;
    }
}