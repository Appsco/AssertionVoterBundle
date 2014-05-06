<?php

namespace Appsco\AssertionVoterBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DecisionMakerRegistrationPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $roleResolverDef = $container->getDefinition('appsco.assertion.role_resolver');

        foreach ($container->findTaggedServiceIds('appsco.assertion.decision_maker') as $serviceId => $tags) {
            foreach ($tags as $attrs) {
                $roleResolverDef->addMethodCall('registerDecisionMaker', [new Reference($serviceId), $attrs['alias']]);
            }
        }
    }
} 