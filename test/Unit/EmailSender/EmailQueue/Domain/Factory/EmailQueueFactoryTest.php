<?php

namespace Test\Unit\EmailSender\EmailQueue\Domain\Factory;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\EmailQueue\Application\Catalog\EmailQueuePropertyNames;
use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;
use EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

class EmailQueueFactoryTest extends TestCase
{
    /**
     * Test create method.
     */
    public function testCreate()
    {
        $unSignedInteger = (new Mockery($this))->getUnSignedIntegerMock(1);
        $emailLog        = (new Mockery($this))->getEmailLogMock();

        $emailLog->expects($this->once())
            ->method('getEmailLogId')
            ->willReturn($unSignedInteger);

        $emailLog->expects($this->once())
            ->method('getComposedEmailId')
            ->willReturn($unSignedInteger);

        $emailLog->expects($this->once())
            ->method('getDelay')
            ->willReturn($unSignedInteger);

        $expected          = new EmailQueue($unSignedInteger, $unSignedInteger, $unSignedInteger);
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
            EmailQueuePropertyNames::EMAIL_LOG_ID      => $testValue,
            EmailQueuePropertyNames::COMPOSED_EMAIL_ID => $testValue,
            EmailQueuePropertyNames::DELAY             => $testValue,
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
