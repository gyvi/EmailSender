<?php

namespace EmailSender\MessageQueue\Domain\Factory;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageQueue\Application\Catalog\MessageQueuePropertyNames;
use EmailSender\MessageQueue\Domain\Aggregator\MessageQueue;

/**
 * Class MessageQueueFactory
 *
 * @package EmailSender\MessageQueue
 */
class MessageQueueFactory
{
    /**
     * @param \EmailSender\MessageLog\Domain\Aggregate\MessageLog $messageLog
     *
     * @return \EmailSender\MessageQueue\Domain\Aggregator\MessageQueue
     */
    public function createFromMessageLog(MessageLog $messageLog): MessageQueue
    {
        return new MessageQueue($messageLog->getMessageLogId(), $messageLog->getMessageId(), $messageLog->getDelay());
    }

    /**
     * @param array $messageQueue
     *
     * @return \EmailSender\MessageQueue\Domain\Aggregator\MessageQueue
     */
    public function createFromArray(array $messageQueue): MessageQueue
    {
        return new MessageQueue(
            new UnsignedInteger($messageQueue[MessageQueuePropertyNames::MESSAGE_LOG_ID]),
            new UnsignedInteger($messageQueue[MessageQueuePropertyNames::MESSAGE_ID]),
            new UnsignedInteger($messageQueue[MessageQueuePropertyNames::DELAY])
        );
    }
}
