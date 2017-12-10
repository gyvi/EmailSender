<?php

namespace Test\Helper\EmailSender\Mockery;

use EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime;

/**
 * Trait DateTimeMock
 *
 * @package Test\Helper
 */
trait DateTimeMock
{
    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getDateTimeMock(): DateTime
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $dateTimeMock = $testCase->getMockBuilder(DateTime::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $dateTimeMock;
    }
}
