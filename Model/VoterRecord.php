<?php

namespace Appsco\AssertionVoterBundle\Model;

class VoterRecord implements VoterRecordInterface
{
    /**
     * @var string
     */
    private $issuer;

    /**
     * @var string
     */
    private $attribute;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $role;

    public function __construct($issuer, $attribute, $value, $role)
    {
        $this->issuer = $issuer;
        $this->attribute = $attribute;
        $this->value = $value;
        $this->role = $role;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * {@inheritDoc}
     */
    public function getRole()
    {
        return $this->role;
    }
} 