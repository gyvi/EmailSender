<?php

namespace Test\Helper\EmailSender\Mockery;

use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;

/**
 * Trait EmailQueueMock
 *
 * @package Test\Helper
 */
trait EmailQueueMock
{
    /**
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getEmailQueueMock(): EmailQueue
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $emailQueueMock = $testCase->getMockBuilder(EmailQueue::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $emailQueueMock;
    }
}
