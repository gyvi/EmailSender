<?php

namespace EmailSender\MessageQueue\Domain\Builder;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageQueue\Application\Catalog\MessageQueuePropertyList;
use EmailSender\MessageQueue\Domain\Aggregator\MessageQueue;

/**
 * Class MessageQueueBuilder
 *
 * @package EmailSender\MessageQueue
 */
class MessageQueueBuilder
{
    /**
     * @param \EmailSender\MessageLog\Domain\Aggregate\MessageLog $messageLog
     *
     * @return \EmailSender\MessageQueue\Domain\Aggregator\MessageQueue
     */
    public function buildMessageQueueFromMessageLog(MessageLog $messageLog): MessageQueue
    {
        return new MessageQueue($messageLog->getMessageLogId(), $messageLog->getMessageId(), $messageLog->getDelay());
    }

    /**
     * @param array $messageQueue
     *
     * @return \EmailSender\MessageQueue\Domain\Aggregator\MessageQueue
     */
    public function buildMessageQueueFromArray(array $messageQueue): MessageQueue
    {
        return new MessageQueue(
            new UnsignedInteger($messageQueue[MessageQueuePropertyList::MESSAGE_LOG_ID]),
            new UnsignedInteger($messageQueue[MessageQueuePropertyList::MESSAGE_ID]),
            new UnsignedInteger($messageQueue[MessageQueuePropertyList::DELAY])
        );
    }
}
