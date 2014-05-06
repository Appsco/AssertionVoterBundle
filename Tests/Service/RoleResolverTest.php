<?php

namespace Appsco\AssertionVoterBundle\Tests\Service;

use Appsco\AssertionVoterBundle\Model\VoterRecord;
use Appsco\AssertionVoterBundle\Service\RoleResolver;

class RoleResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RoleResolver
     */
    private $roleResolver;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $decisionManagerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $voterFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $voteRecordProviderMock;

    protected function setUp()
    {
        $this->decisionManagerMock = $this->getMock('BWC\Component\AssertionVoter\DecisionManager');
        $this->voteRecordProviderMock = $this->getMock('Appsco\AssertionVoterBundle\VoterRecordProvider\VoterRecordProviderInterface');
        $this->voterFactoryMock = $this->getMock('Appsco\AssertionVoterBundle\VoterFactory\VoterFactoryInterface');

        $this->roleResolver = new RoleResolver($this->decisionManagerMock, $this->voteRecordProviderMock, $this->voterFactoryMock);
    }

    public function testResolveFirstTime()
    {
        $this->initializationExpectations();

        $this->roleResolver->resolve([]);
    }

    public function testResolveSubsequent()
    {
        $this->initializationExpectations();

        $this->roleResolver->resolve([]);
        $this->roleResolver->resolve([]);
    }

    private function initializationExpectations()
    {
        $this->voteRecordProviderMock->expects($this->once())->method('getVoterRecords')
            ->will($this->returnValue([$this->generateVoterRecord(), $this->generateVoterRecord()]));

        $this->voterFactoryMock->expects($this->exactly(2))->method('create')
            ->with($this->isInstanceOf('Appsco\AssertionVoterBundle\Model\VoterRecordInterface'))
            ->will($this->returnValue($this->getMock('BWC\Component\AssertionVoter\VoterInterface')));

        $this->decisionManagerMock->expects($this->exactly(2))->method('addVoter')
            ->with($this->isInstanceOf('BWC\Component\AssertionVoter\VoterInterface'));
    }

    /**
     * @return VoterRecord
     */
    private function generateVoterRecord()
    {
         return new VoterRecord(md5(rand()), md5(rand()), md5(rand()), md5(rand()));
    }
} 