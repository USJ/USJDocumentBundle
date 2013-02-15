<?php

namespace MDB\DocumentBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mdb_document', 'array');

        $rootNode
            ->children()
                ->arrayNode('document')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('form')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('mdb_document_document')->end()
                                ->scalarNode('name')->defaultValue('mdb_document_document')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('class')->isRequired()
                    ->children()
                        ->arrayNode('model')->isRequired()
                            ->children()
                                ->scalarNode('document')->isRequired()->end()
                                ->scalarNode('file')->isRequired()->end()
                                ->scalarNode('link')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('service')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('manager')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('document')->cannotBeEmpty()->defaultValue('mdb_document.manager.document.default')->end()
                                ->scalarNode('file')->cannotBeEmpty()->defaultValue('mdb_document.manager.file.default')->end()
                                ->scalarNode('link')->cannotBeEmpty()->defaultValue('mdb_document.manager.link.default')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
