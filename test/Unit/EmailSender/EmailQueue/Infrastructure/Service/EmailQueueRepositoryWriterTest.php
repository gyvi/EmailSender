<?php

namespace Test\Unit\EmailSender\EmailQueue\Infrastructure\Service;

use EmailSender\EmailQueue\Infrastructure\Factory\AMQPMessageFactory;
use EmailSender\EmailQueue\Infrastructure\Service\EmailQueueRepositoryWriter;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailQueueRepositoryWriterTest
 *
 * @package Test\Unit\EmailSender\EmailQueue
 */
class EmailQueueRepositoryWriterTest extends TestCase
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

        /** @var \PhpAmqpLib\Message\AMQPMessage|\PHPUnit_Framework_MockObject_MockObject $amqpMessage */
        $amqpMessage = $this->getMockBuilder(AMQPMessage::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \EmailSender\EmailQueue\Infrastructure\Factory\AMQPMessageFactory|
         * \PHPUnit_Framework_MockObject_MockObject $amqpMessageFactory */
        $amqpMessageFactory = $this->getMockBuilder(AMQPMessageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $amqpMessageFactory->expects($this->once())
            ->method('create')
            ->willReturn($amqpMessage);

        $queueConnection = (new Mockery($this))->getQueueConnectionMock();

        $queueConnection->expects($this->once())
            ->method('channel')
            ->willReturn($channel);

        $queueConnection->expects($this->once())
            ->method('close')
            ->willReturn(null);

        $queueService = (new Mockery($this))->getQueueServiceMock($queueConnection);

        $emailQueueRepositoryWriter = new EmailQueueRepositoryWriter(
            $queueService,
            $amqpMessageFactory,
            '',
            ''
        );

        $emailQueue = (new Mockery($this))->getEmailQueueMock();

        try {
            $emailQueueRepositoryWriter->add($emailQueue);
        } catch (\Throwable $e) {
            $this->fail();
        }
    }
}
