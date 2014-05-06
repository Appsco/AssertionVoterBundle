<?php

namespace Appsco\AssertionVoterBundle\VoterFactory;

use Appsco\AssertionVoterBundle\Model\VoterRecordInterface;
use BWC\Component\AssertionVoter\VoterSimple;

class SimpleVoterFactory implements VoterFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(VoterRecordInterface $voterRecord)
    {
        return new VoterSimple(
            $voterRecord->getIssuer(),
            $voterRecord->getAttribute(),
            $voterRecord->getValue(),
            $voterRecord->getRole()
        );
    }
} 