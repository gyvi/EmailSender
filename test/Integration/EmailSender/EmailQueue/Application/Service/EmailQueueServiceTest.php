<?php

namespace Test\Integration\EmailSender\EmailQueue\Application\Service;

use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\EmailQueue\Application\Catalog\EmailQueuePropertyNamesList;
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

        $emailLog = (new Mockery($this))->getEmailLogMock();

        $expected = new EmailStatus(EmailStatusList::STATUS_QUEUED);

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

    /**
     * Test get method.
     */
    public function testGet()
    {
        $logger               = (new Mockery($this))->getLoggerMock();
        $queueService         = (new Mockery($this))->getQueueServiceMock();
        $queueServiceSettings = [];

        $emailLogId      = 1;
        $composedEmailId = 1;
        $delay           = 1;

        $emailQueueArray = [
            EmailQueuePropertyNamesList::EMAIL_LOG_ID      => $emailLogId,
            EmailQueuePropertyNamesList::COMPOSED_EMAIL_ID => $composedEmailId,
            EmailQueuePropertyNamesList::DELAY             => $delay,
        ];

        $expected = new EmailQueue(
            new UnsignedInteger($emailQueueArray[EmailQueuePropertyNamesList::EMAIL_LOG_ID]),
            new UnsignedInteger($emailQueueArray[EmailQueuePropertyNamesList::COMPOSED_EMAIL_ID]),
            new UnsignedInteger($emailQueueArray[EmailQueuePropertyNamesList::DELAY])
        );

        $emailQueueService = new EmailQueueService($logger, $queueService, $queueServiceSettings);

        $this->assertEquals($expected, $emailQueueService->get($emailQueueArray));
    }

    /**
     * Test get method with exception.
     *
     * @expectedException \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     * @expectedExceptionMessage Invalid email queue.
     */
    public function testGetWithException()
    {
        $logger = (new Mockery($this))->getLoggerMock();

        $logger->expects($this->once())
            ->method('alert')
            ->willReturn(null);

        $queueService         = (new Mockery($this))->getQueueServiceMock();
        $queueServiceSettings = [];

        $emailQueueService = new EmailQueueService($logger, $queueService, $queueServiceSettings);

        $emailQueueService->get([]);
    }
}
