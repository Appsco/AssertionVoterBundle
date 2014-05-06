<?php

namespace Appsco\AssertionVoterBundle\VoterFactory;

use Appsco\AssertionVoterBundle\Model\VoterRecordInterface;
use BWC\Component\AssertionVoter\VoterInterface;

/**
 * Builds voters from voter records
 */
interface VoterFactoryInterface
{
    /**
     * Creates a VoterInterface from information provided in VoterRecordInterface
     *
     * @param VoterRecordInterface $voterRecord
     *
     * @return VoterInterface
     */
    public function create(VoterRecordInterface $voterRecord);
} 