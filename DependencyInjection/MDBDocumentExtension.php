<?php

namespace MDB\DocumentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MDBDocumentExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (array_key_exists('acl', $config)) {
            $loader->load('acl.yml');
            $container->setAlias('mdb_document.acl.document', $config['service']['acl']['document']);
        }

        // load configuration to container's parameter for later use.
        $container->setParameter('mdb_document.model.document.class', $config['class']['model']['document']);
        $container->setParameter('mdb_document.model.file.class', $config['class']['model']['file']);

        $container->setAlias('mdb_document.manager.document', $config['service']['manager']['document']);
        $container->setAlias('mdb_document.manager.file', $config['service']['manager']['file']);

    }
}
