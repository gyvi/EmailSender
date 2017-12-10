<?php

namespace Test\Unit\EmailSender\EmailQueue\Domain\Factory;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\EmailQueue\Application\Catalog\EmailQueuePropertyNamesList;
use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;
use EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailQueueFactoryTest
 *
 * @package Test\Unit\EmailSender\EmailQueue
 */
class EmailQueueFactoryTest extends TestCase
{
    /**
     * Test create method.
     */
    public function testCreate()
    {
        $emailLogId      = 1;
        $composedEmailId = 2;
        $delay           = 3;

        $emailLogIdMock      = (new Mockery($this))->getUnSignedIntegerMock($emailLogId);
        $composedEmailIdMock = (new Mockery($this))->getUnSignedIntegerMock($composedEmailId);
        $delayMock           = (new Mockery($this))->getUnSignedIntegerMock($delay);

        $emailLog = (new Mockery($this))->getEmailLogMock(
            $emailLogId,
            $composedEmailId,
            null,
            null,
            null,
            null,
            null,
            null,
            $delay
        );

        $expected          = new EmailQueue($emailLogIdMock, $composedEmailIdMock, $delayMock);
        $emailQueueFactory = new EmailQueueFactory();

        $this->assertEquals($expected, $emailQueueFactory->create($emailLog));
    }

    /**
     * Test createFromArray method.
     */
    public function testCreateFromArray()
    {
        $testValue = 1;

        $testArray = [
            EmailQueuePropertyNamesList::EMAIL_LOG_ID      => $testValue,
            EmailQueuePropertyNamesList::COMPOSED_EMAIL_ID => $testValue,
            EmailQueuePropertyNamesList::DELAY             => $testValue,
        ];

        $expected = new EmailQueue(
            new UnsignedInteger($testValue),
            new UnsignedInteger($testValue),
            new UnsignedInteger($testValue)
        );

        $emailQueueFactory = new EmailQueueFactory();

        $this->assertEquals($expected, $emailQueueFactory->createFromArray($testArray));
    }
}
