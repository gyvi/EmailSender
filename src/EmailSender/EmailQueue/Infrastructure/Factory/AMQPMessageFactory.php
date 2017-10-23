<?php

namespace EmailSender\EmailQueue\Infrastructure\Factory;

use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

/**
 * Class AMQPMessageFactory
 *
 * @package EmailSender\EmailQueue
 */
class AMQPMessageFactory
{
    /**
     * @param \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue $emailQueue
     *
     * @return \PhpAmqpLib\Message\AMQPMessage
     */
    public function create(EmailQueue $emailQueue): AMQPMessage
    {
        $messageProperties = [
            'delivery_mode'       => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            'application_headers' => new AMQPTable([
                'x-delay' => $emailQueue->getDelay()->getValue() * 1000 // milliseconds
            ]),
        ];

        return new AMQPMessage(json_encode($emailQueue), $messageProperties);
    }
}
