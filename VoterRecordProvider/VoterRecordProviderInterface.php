<?php

namespace Appsco\AssertionVoterBundle\VoterRecordProvider;

use Appsco\AssertionVoterBundle\Model\VoterRecordInterface;

/**
 * Provides access to voter records
 */
interface VoterRecordProviderInterface
{
    /**
     * Gets list of all voter records
     *
     * @return VoterRecordInterface[]
     */
    public function getVoterRecords();
} 