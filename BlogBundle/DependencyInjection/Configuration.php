<?php

namespace BlogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
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
        $rootNode = $treeBuilder->root('blog');

        $rootNode
            ->children()
                ->booleanNode('fixtures_dev')->defaultTrue()->end()
                ->arrayNode('mapping')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
