<?php

namespace Appsco\AssertionVoterBundle\Tests\Service;

use Appsco\AssertionVoterBundle\Model\VoterRecord;
use Appsco\AssertionVoterBundle\Service\RoleResolver;
use BWC\Component\AssertionVoter\SimpleDecisionMaker;

class RoleResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RoleResolver
     */
    private $roleResolver;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $decisionMakerMock;

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
        $this->decisionMakerMock = $this->mockDecisionMaker();
        $this->voteRecordProviderMock = $this->getMock('Appsco\AssertionVoterBundle\VoterRecordProvider\VoterRecordProviderInterface');
        $this->voterFactoryMock = $this->getMock('Appsco\AssertionVoterBundle\VoterFactory\VoterFactoryInterface');

        $this->roleResolver = new RoleResolver($this->voteRecordProviderMock, $this->voterFactoryMock);
        $this->roleResolver->registerDecisionMaker($this->decisionMakerMock, 'simple');
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

    public function testResolveSimpleDecisionMaker()
    {
        $this->initializationExpectations();

        $this->decisionMakerMock->expects($this->once())->method('evaluate');

        $this->roleResolver->resolve([]);
    }

    public function testResolveNotSimpleDecisionMaker()
    {
        $this->initializationExpectations();

        $notSoSimpleDM = $this->mockDecisionMaker();
        $notSoSimpleDM->expects($this->once())->method('evaluate');
        $this->roleResolver->registerDecisionMaker($notSoSimpleDM, $makerKey = 'not_so_simple');

        $this->roleResolver->resolve([], $makerKey);
    }

    public function testDuplicateDecisionMakerRegistration()
    {
        $this->setExpectedException('Appsco\AssertionVoterBundle\Exception\ConfigurationException');

        $this->roleResolver->registerDecisionMaker($this->mockDecisionMaker(), 'simple');
    }

    private function initializationExpectations()
    {
        $this->voteRecordProviderMock->expects($this->once())->method('getVoterRecords')
            ->will($this->returnValue([$this->generateVoterRecord(), $this->generateVoterRecord()]));

        $this->voterFactoryMock->expects($this->exactly(2))->method('create')
            ->with($this->isInstanceOf('Appsco\AssertionVoterBundle\Model\VoterRecordInterface'))
            ->will($this->returnValue($this->getMock('BWC\Component\AssertionVoter\VoterInterface')));
    }

    /**
     * @return VoterRecord
     */
    private function generateVoterRecord()
    {
         return new VoterRecord(md5(rand()), md5(rand()), md5(rand()), md5(rand()));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function mockDecisionMaker()
    {
        return $this->getMock('BWC\Component\AssertionVoter\DecisionMakerInterface');
    }
} 