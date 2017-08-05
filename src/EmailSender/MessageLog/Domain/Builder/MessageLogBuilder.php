<?php

namespace EmailSender\MessageLog\Domain\Builder;

use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageLog\Domain\Aggregator\MessageLog;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;

/**
 * Class MessageLogBuilder
 *
 * @package EmailSender\MessageLog
 */
class MessageLogBuilder
{
    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message           $message
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @return \EmailSender\MessageLog\Domain\Aggregator\MessageLog
     */
    public function buildMessageLogFromMessage(Message $message, MessageStore $messageStore): MessageLog
    {
        return new MessageLog(
            $messageStore->getMessageId(),
            $message->getFrom(),
            $messageStore->getRecipients(),
            $message->getSubject(),
            $message->getDelay()
        );
    }
}
