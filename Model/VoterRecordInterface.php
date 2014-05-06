<?php

namespace Appsco\AssertionVoterBundle\Model;

/**
 * @ORM\Entity
 */
interface VoterRecordInterface
{
    /**
     * @return string
     */
    public function getAttribute();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @return string
     */
    public function getIssuer();

    /**
     * @return string
     */
    public function getRole();
}