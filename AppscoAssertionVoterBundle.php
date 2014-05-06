<?php

namespace Appsco\AssertionVoterBundle;

use Appsco\AssertionVoterBundle\DependencyInjection\Compiler\DecisionMakerRegistrationPass;
use Appsco\AssertionVoterBundle\DependencyInjection\Compiler\RegisterMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppscoAssertionVoterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DecisionMakerRegistrationPass());
        $this->addMappingPasses($container);
    }

    private function addMappingPasses(ContainerBuilder $container)
    {
        $mappings = array(
            realpath(__DIR__ . '/Resources/config/mapping') => 'Appsco\AssertionVoterBundle\Entity',
        );

        $container->addCompilerPass(RegisterMappingsPass::createOrmMappingsPass($mappings));
    }
} 