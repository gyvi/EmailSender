<?php

namespace Test\Unit\EmailSender\EmailQueue\Infrastructure\Factory;

use EmailSender\EmailQueue\Infrastructure\Factory\AMQPMessageFactory;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Test\Helper\EmailSender\Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Class AMQPMessageFactoryTest
 *
 * @package Test\Unit\EmailSender\EmailQueue
 */
class AMQPMessageFactoryTest extends TestCase
{
    /**
     * Test create method.
     */
    public function testCreate()
    {
        $delay = 1;

        $emailQueueToJson = ['delay' => 1];

        $messageProperties = [
            'delivery_mode'       => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            'application_headers' => new AMQPTable([
                'x-delay' => $delay * 1000
            ]),
        ];

        $delayMock  = (new Mockery($this))->getUnSignedIntegerMock($delay);

        $emailQueue = (new Mockery($this))->getEmailQueueMock();

        $emailQueue->expects($this->once())
            ->method('jsonSerialize')
            ->willReturn($emailQueueToJson);

        $emailQueue->expects($this->once())
            ->method('getDelay')
            ->willReturn($delayMock);

        $expected = new AMQPMessage(json_encode($emailQueueToJson), $messageProperties);

        $amqpMessageFactory = new AMQPMessageFactory();

        $this->assertEquals($expected, $amqpMessageFactory->create($emailQueue));
    }
}
