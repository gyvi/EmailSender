<?php

namespace Test\Integration\EmailSender\EmailQueue\Application\Service;

use EmailSender\EmailQueue\Application\Service\EmailQueueService;
use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;
use PhpAmqpLib\Channel\AMQPChannel;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;
use Exception;

/**
 * Class EmailQueueServiceTest
 *
 * @package Test\Unit\EmailSender\EmailQueue
 */
class EmailQueueServiceTest extends TestCase
{
    /**
     * Test add method.
     */
    public function testAdd()
    {
        $emailLogId      = 1;
        $composedEmailId = 2;
        $delay           = 3;

        $emailLogIdMock      = (new Mockery($this))->getUnSignedIntegerMock($emailLogId);
        $composedEmailIdMock = (new Mockery($this))->getUnSignedIntegerMock($composedEmailId);
        $delayMock           = (new Mockery($this))->getUnSignedIntegerMock($delay);

        /** @var \PhpAmqpLib\Channel\AMQPChannel|\PHPUnit_Framework_MockObject_MockObject $channel */
        $channel = $this->getMockBuilder(AMQPChannel::class)
            ->disableOriginalConstructor()
            ->setMethods([
                    'channel',
                    'exchange_declare',
                    'queue_declare',
                    'queue_bind',
                    'basic_publish',
                    'close']
            )->getMock();

        $queueConnection  = (new Mockery($this))->getQueueConnectionMock();

        $queueConnection->expects($this->once())
            ->method('channel')
            ->willReturn($channel);

        $queueConnection->expects($this->once())
            ->method('close')
            ->willReturn(null);

        $logger               = (new Mockery($this))->getLoggerMock();
        $queueService         = (new Mockery($this))->getQueueServiceMock($queueConnection);
        $queueServiceSettings = [
            'queue'    => 'emailSendQueue',
            'exchange' => 'emailSender',
        ];

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

        $expected = new EmailQueue(
            $emailLogIdMock,
            $composedEmailIdMock,
            $delayMock
        );

        $emailQueueService = new EmailQueueService($logger, $queueService, $queueServiceSettings);

        $this->assertEquals($expected, $emailQueueService->add($emailLog));
    }

    /**
     * Test add method with exception throw.
     *
     * @expectedException \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     * @expectedExceptionMessage Something went wrong with the queue.
     */
    public function testAddWithException()
    {
        $logger = (new Mockery($this))->getLoggerMock();

        $logger->expects($this->once())
            ->method('alert')
            ->willReturn(null);

        $queueConnection      = (new Mockery($this))->getQueueConnectionMock();
        $queueService         = (new Mockery($this))->getQueueServiceMock($queueConnection);
        $queueServiceSettings = [
            'queue'    => 'emailSendQueue',
            'exchange' => 'emailSender',
        ];

        $emailLog = (new Mockery($this))->getEmailLogMock();

        $emailLog->expects($this->once())
            ->method('getEmailLogId')
            ->willThrowException(new Exception('Something'));

        $emailQueueService = new EmailQueueService($logger, $queueService, $queueServiceSettings);

        $emailQueueService->add($emailLog);
    }
}
