<?php

namespace EmailSender\MessageQueue\Infrastructure\Builder;

use EmailSender\MessageQueue\Domain\Aggregator\MessageQueue;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

/**
 * Class MessageQueueRepositoryAMQPBuilder
 *
 * @package EmailSender\MessageQueue
 */
class AMQPMessageBuilder
{
    /**
     * @param \EmailSender\MessageQueue\Domain\Aggregator\MessageQueue $messageQueue
     *
     * @return \PhpAmqpLib\Message\AMQPMessage
     */
    public function buildAMQPMessage(MessageQueue $messageQueue)
    {
        $messageProperties = [
            'delivery_mode'       => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            'application_headers' => new AMQPTable([
                'x-delay' => $messageQueue->getDelay()->getValue()
            ]),
        ];

        return new AMQPMessage(json_encode($messageQueue), $messageProperties);
    }
}
