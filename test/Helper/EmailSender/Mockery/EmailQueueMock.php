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
     * @param int|null   $emailLogId
     * @param int|null   $composedEmailId
     * @param int|null   $delay
     * @param array|null $json
     *
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getEmailQueueMock(
        ?int $emailLogId = null,
        ?int $composedEmailId = null,
        ?int $delay = null,
        ?array $json = null
    ): EmailQueue {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $emailQueueMock = $testCase->getMockBuilder(EmailQueue::class)
            ->disableOriginalConstructor()
            ->getMock();

        if ($emailLogId !== null) {
            $emailQueueMock->expects($testCase->any())
                ->method('getEmailLogId')
                ->willReturn(
                    $this->getUnSignedIntegerMock($emailLogId)
                );
        }

        if ($composedEmailId !== null) {
            $emailQueueMock->expects($testCase->any())
                ->method('getComposedEmailId')
                ->willReturn(
                    $this->getUnSignedIntegerMock($composedEmailId)
                );
        }

        if ($delay !== null) {
            $emailQueueMock->expects($testCase->any())
                ->method('getDelay')
                ->willReturn(
                    $this->getUnSignedIntegerMock($delay)
                );
        }

        if ($json) {
            $emailQueueMock->expects($testCase->any())
                ->method('jsonSerialize')
                ->willReturn(
                    $json
                );
        }

        return $emailQueueMock;
    }
}
