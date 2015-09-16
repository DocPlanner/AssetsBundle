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
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('docplanner_assets');

        $nodeBuilder = $rootNode->children();
        $this->addNode($nodeBuilder, 'style');
        $this->addNode($nodeBuilder, 'script');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }

    /**
     * @param NodeBuilder $node
     * @param string      $name
     *
     * @return NodeBuilder
     */
    public function addNode(NodeBuilder $node, $name)
    {
        // @formatter:off
        /** @noinspection PhpUndefinedMethodInspection */
        $node->arrayNode($name)
                ->addDefaultsIfNotSet()
                ->children()
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
            ->end();
            // @formatter:on

        return $node;
    }
}
