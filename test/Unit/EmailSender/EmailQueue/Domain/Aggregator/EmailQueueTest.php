<?php

namespace Test\Unit\EmailSender\EmailQueue\Domain\Aggregator;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\EmailQueue\Application\Catalog\EmailQueuePropertyNamesList;
use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailQueueTest
 *
 * @package Test\Unit\EmailSender\EmailQueue
 */
class EmailQueueTest extends TestCase
{
    /**
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue
     */
    public function getEmailQueue(): EmailQueue
    {
        $unSignedInteger = $this->getUnSignedInteger();

        return new EmailQueue(
            $unSignedInteger,
            $unSignedInteger,
            $unSignedInteger
        );
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnSignedInteger|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getUnSignedInteger(): UnsignedInteger
    {
        return (new Mockery($this))->getUnSignedIntegerMock(0);
    }

    /**
     * Test getEmailLogId method.
     */
    public function testGetEmailLogId()
    {
        $emailQueue = $this->getEmailQueue();

        $this->assertEquals($this->getUnSignedInteger(), $emailQueue->getEmailLogId());
    }

    /**
     * Test getComposedEmailId method.
     */
    public function testGetComposedEmailId()
    {
        $emailQueue = $this->getEmailQueue();

        $this->assertEquals($this->getUnSignedInteger(), $emailQueue->getComposedEmailId());
    }

    /**
     * Test getDelay method.
     */
    public function testGetDelay()
    {
        $emailQueue = $this->getEmailQueue();

        $this->assertEquals($this->getUnSignedInteger(), $emailQueue->getDelay());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $intValue = 1;

        $unSignedInteger = $this->getUnSignedInteger();

        $unSignedInteger->expects($this->any())
            ->method('jsonSerialize')
            ->willReturn(1);

        $emailQueue = new EmailQueue(
            $unSignedInteger,
            $unSignedInteger,
            $unSignedInteger
        );

        $expected = json_encode([
            EmailQueuePropertyNamesList::EMAIL_LOG_ID      => $intValue,
            EmailQueuePropertyNamesList::COMPOSED_EMAIL_ID => $intValue,
            EmailQueuePropertyNamesList::DELAY             => $intValue,
        ]);

        $this->assertEquals($expected, json_encode($emailQueue));
    }
}
