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
                ->scalarNode('voter_factory')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
} 