<?php

namespace Appsco\AssertionVoterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('appsco_assertion_voter');

        $rootNode
            ->children()
                ->scalarNode('voter_record_provider')->isRequired()->end()
                ->scalarNode('voter_record_class')->end()
                ->scalarNode('voter_factory')->isRequired()->end()
            ->end()
            ->validate()
                ->ifTrue(function ($v) {
                    return $v['voter_record_provider'] === 'appsco.assertion.voter_record_provider.orm'
                    && empty($v['voter_record_class']);
                })
                ->thenInvalid('"voter_record_class" configuration parameter must be set when using ORM record provider')
            ->end()
        ;

        return $treeBuilder;
    }
} 