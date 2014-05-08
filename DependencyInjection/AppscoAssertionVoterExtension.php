<?php

namespace Appsco\AssertionVoterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AppscoAssertionVoterExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $configLocator = new FileLocator(__DIR__ . '/../Resources/config');
        $yamlLoader = new YamlFileLoader($container, $configLocator);

        $yamlLoader->load('services.yml');

        $this->defineRoleResolver($config, $container);
        $this->defineMappingsParameter($config, $container);
    }

    private function defineRoleResolver($config, ContainerBuilder $container)
    {
        $roleResolver = new Definition(
            'Appsco\AssertionVoterBundle\Service\RoleResolver',
            [
                $container->getDefinition($config['voter_record_provider']),
                $container->getDefinition($config['voter_factory']),
            ]
        );
        $container->setDefinition('appsco.assertion.role_resolver', $roleResolver);
    }

    private function defineMappingsParameter($config, ContainerBuilder $container)
    {
        switch ($config['voter_record_provider']) {
            case 'appsco.assertion.voter_record_provider.orm':
                $container->setParameter('appsco.assertion.backend_type.orm', true);
                $container->setParameter('appsco.assertion.voter_record.orm.class', $config['voter_record_class']);
                break;
        }
    }
} 