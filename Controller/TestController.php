<?php

namespace Appsco\AssertionVoterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function indexAction()
    {
        $roleResolver = $this->get('appsco.assertion.role_resolver');

        $roles = $roleResolver->resolve(
            [
                'appsco' => [
                    'blah' => 1,
                    'omg' => 1,
                ],
            ],
            'custom'
        );

        var_dump($roles);

        return new Response();
    }
} 