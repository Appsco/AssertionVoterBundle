<?php

namespace Appsco\AssertionVoterBundle\VoterRecordProvider;

use Doctrine\ORM\EntityManager;

class DoctrineOrmVoterRecordProvider implements VoterRecordProviderInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $voterRecordClass;

    public function __construct(EntityManager $em, $voterRecordClass)
    {
        $this->em = $em;
        $this->voterRecordClass = $voterRecordClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getVoterRecords()
    {
        return $this->em->getRepository($this->voterRecordClass)->findAll();
    }
} 