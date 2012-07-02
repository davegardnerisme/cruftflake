<?php

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    private $machineId = 1;
    
    private $timer;
    private $config;
    
    public function setUp()
    {
        $this->timer = $this->getMockBuilder('\Davegardnerisme\CruftFlake\TimerInterface')
                            ->disableOriginalConstructor()
                            ->getMock();
        $this->config = $this->getMockBuilder('\Davegardnerisme\CruftFlake\ConfigInterface')
                            ->disableOriginalConstructor()
                            ->getMock();
    }
    
    private function buildSystemUnderTest()
    {
        $this->config->expects($this->once())
                     ->method('getMachine')
                     ->will($this->returnValue($this->machineId));
        return new \Davegardnerisme\CruftFlake\Generator($this->config, $this->timer);
    }
    
    public function testConstructs()
    {
        $cf = $this->buildSystemUnderTest();
        $this->assertInstanceOf('\Davegardnerisme\CruftFlake\Generator', $cf);
    }
    
    public function testFailsWithBadMachineIdString()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->machineId = '1';
        $cf = $this->buildSystemUnderTest();
    }
    
    public function testFailsWithBadMachineIdNegative()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->machineId = -1;
        $cf = $this->buildSystemUnderTest();
    }

    public function testFailsWithBadMachineIdTooBig()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->machineId = 1024;
        $cf = $this->buildSystemUnderTest();
    }

    public function testFailsWithBadMachineIdFloat()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->machineId = 1.1;
        $cf = $this->buildSystemUnderTest();
    }

    public function testLargestPossibleMachineId()
    {
        $this->machineId = 1023;
        $cf = $this->buildSystemUnderTest();
        $this->assertInstanceOf('\Davegardnerisme\CruftFlake\Generator', $cf);
    }
    
    public function testGenerate()
    {
        $this->timer->expects($this->once())
                    ->method('getUnixTimestamp')
                    ->will($this->returnValue(1341246960000));
        $cf = $this->buildSystemUnderTest();
        $id = $cf->generate();
        $this->assertTrue(is_string($id));
    }
}
