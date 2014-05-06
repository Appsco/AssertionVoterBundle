<?php

namespace Appsco\AssertionVoterBundle\Service;

use Appsco\AssertionVoterBundle\Exception\InvalidArgumentException;
use Appsco\AssertionVoterBundle\VoterFactory\VoterFactoryInterface;
use Appsco\AssertionVoterBundle\VoterRecordProvider\VoterRecordProviderInterface;
use BWC\Component\AssertionVoter\DecisionMakerInterface;
use BWC\Component\AssertionVoter\VoterInterface;

class RoleResolver
{
    /**
     * @var VoterRecordProviderInterface
     */
    private $voterRecordProvider;

    /**
     * @var VoterFactoryInterface
     */
    private $voterFactory;

    /**
     * @var VoterInterface[]
     */
    private $voters;

    /**
     * @var DecisionMakerInterface[]
     */
    private $decisionMakers;

    /**
     * @var bool
     */
    private $votersInitialized = false;

    public function __construct(VoterRecordProviderInterface $voterRecordProvider, VoterFactoryInterface $voterFactory)
    {
        $this->voterRecordProvider = $voterRecordProvider;
        $this->voterFactory = $voterFactory;
    }

    /**
     * Creates role list based on provided info
     *
     * @param array $info
     * @param string $decisionMakerName
     *
     * @return array Role list
     */
    public function resolve(array $info, $decisionMakerName = 'simple')
    {
        $this->initializeVoters();

        return $this->resolveDecisionMaker($decisionMakerName)->evaluate($info, $this->voters);
    }

    /**
     * @param DecisionMakerInterface $decisionMaker
     * @param string $name
     */
    public function registerDecisionMaker(DecisionMakerInterface $decisionMaker, $name)
    {
        $this->decisionMakers[$name] = $decisionMaker;
    }

    private function initializeVoters()
    {
        if (false === $this->votersInitialized) {
            $voterRecords = $this->voterRecordProvider->getVoterRecords();
            foreach ($voterRecords as $voterRecord) {
                $voter = $this->voterFactory->create($voterRecord);
                $this->voters[] = $voter;
            }

            $this->votersInitialized = true;
        }
    }

    /**
     * @param string $decisionMakerName
     *
     * @return DecisionMakerInterface
     *
     * @throws \Appsco\AssertionVoterBundle\Exception\InvalidArgumentException
     */
    private function resolveDecisionMaker($decisionMakerName)
    {
        if (!isset($this->decisionMakers[$decisionMakerName])) {
            throw new InvalidArgumentException(sprintf(
                'Decision maker "%s" not found. Decision makers available are: %s. Did you forget to tag your decision maker service?',
                $decisionMakerName,
                '("' . implode('", "', array_keys($this->decisionMakers)) . '")'
            ));
        }

        return $this->decisionMakers[$decisionMakerName];
    }
} 