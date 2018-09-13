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
        $treeBuilder = new TreeBuilder;
        $nodeBuilder = $treeBuilder->root('docplanner_assets')->children();

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
        /** @noinspection PhpUndefinedMethodInspection */
        $node->arrayNode('types')
                ->useAttributeAsKey('type')
                ->prototype('array')
                ->addDefaultsIfNotSet()
                ->children()
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
        // @formatter:off
        /** @noinspection PhpUndefinedMethodInspection */
        $nodeBuilder->scalarNode('use_revisions')
                        ->defaultTrue()
                    ->end()
                    ->scalarNode('base_host')
                        ->isRequired()
                    ->end()
                    ->scalarNode('base_path')
                        ->isRequired()
                    ->end()
        			->scalarNode('manifest_file')
					->end();
        // @formatter:on

        return $this;
    }
}
