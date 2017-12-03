<?php

namespace Test\Helper\EmailSender;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Test\Helper\EmailSender\Mockery\ServiceMock;
use Test\Helper\EmailSender\Mockery\ValueObjectMock;

/**
 * Class Mockery
 *
 * @package Test\Helper
 */
class Mockery
{
    use ValueObjectMock;
    use ServiceMock;

    /**
     * @var \PHPUnit\Framework\TestCase
     */
    private $testCase;

    /**
     * Mockery constructor.
     *
     * @param \PHPUnit\Framework\TestCase $testCase
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * @param string $valueObject
     * @param mixed  $value
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getValueObjectMock(string $valueObject, $value): PHPUnit_Framework_MockObject_MockObject
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $valueObjectMock = $this->testCase->getMockBuilder($valueObject)
            ->disableOriginalConstructor()
            ->getMock();

        $valueObjectMock->expects($this->testCase->any())
            ->method('getValue')
            ->willReturn($value);

        return $valueObjectMock;
    }
}
