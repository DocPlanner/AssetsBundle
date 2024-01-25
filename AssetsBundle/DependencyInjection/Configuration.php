<?php

namespace Docplanner\AssetsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('docplanner_assets');
        /** @noinspection PhpUndefinedMethodInspection */
        $rootNode = !method_exists($treeBuilder, 'getRootNode') ? $treeBuilder->root('docplanner_assets') : $treeBuilder->getRootNode();

        $nodeBuilder = $rootNode->children();

        $this->addOptions($nodeBuilder)
              ->addTypes($nodeBuilder);

        return $treeBuilder;
    }

    /**
     * @param NodeBuilder $node
     *
     * @return $this
     */
    public function addTypes(NodeBuilder $node)
    {
        // @formatter:off
        $node->arrayNode('types')
                ->useAttributeAsKey('type')
                ->prototype('array')
                ->addDefaultsIfNotSet()
                ->children()
	                ->scalarNode('manifest_file')
	                ->end()
                    ->arrayNode('manifest_assets')
						->prototype('scalar')
						->end()
                    ->end()
                    ->arrayNode('assets')
                    ->defaultValue([])
                    ->prototype('array')
                        ->children()
                            ->scalarNode('src')
                                ->isRequired()
                            ->end()
                            ->scalarNode('inline')
                                ->defaultFalse()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('groups')
                    ->defaultValue([])
                    ->prototype('array')
                        ->children()
                            ->booleanNode('default')
                                ->defaultFalse()
                            ->end()
                            ->arrayNode('assets')
                                ->prototype('scalar')
                                ->end()
                            ->end()
                            ->arrayNode('routes')
                                ->defaultValue([])
                                ->prototype('scalar')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
        // @formatter:on

        return $this;
    }

    /**
     * @param NodeBuilder $nodeBuilder
     *
     * @return $this
     */
    private function addOptions(NodeBuilder $nodeBuilder)
    {
        $nodeBuilder->scalarNode('use_revisions')->defaultTrue()->end();
        $nodeBuilder->scalarNode('base_host')->isRequired()->end();
        $nodeBuilder->scalarNode('base_path')->isRequired()->end();

        return $this;
    }
}
