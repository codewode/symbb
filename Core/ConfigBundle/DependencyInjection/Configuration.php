<?
namespace SymBB\Core\ConfigBundle\DependencyInjection; 

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface 
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sym_bb_core_config');

        $rootNode
            ->children()
                ->arrayNode('database') 
                    ->children()
                        ->scalarNode('table_prefix') 
                            ->defaultValue('symbb_')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('template') 
                    ->children()
                        ->scalarNode('acp') 
                            ->defaultValue('SymBBTemplateAcpBundle')
                        ->end()
                        ->scalarNode('forum') 
                            ->defaultValue('SymBBTemplateSimpleBundle')
                        ->end()
                        ->scalarNode('portal') 
                            ->defaultValue('SymBBTemplateSimpleBundle')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}