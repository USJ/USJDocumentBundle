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
                        // ->arrayNode('asset')->addDefaultsIfNotSet()
                        //     ->children()
                        //         ->scalarNode('type')->defaultValue('mdb_asset_asset_parent')->end()
                        //     ->end()
                        // ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
