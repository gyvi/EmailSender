<?php

namespace EmailSender\MessageQueue\Domain\Contract;

use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;

/**
 * Interface SMTPSenderInterface
 *
 * @package EmailSender\MessageQueue\Domain\Contract
 */
interface SMTPSenderInterface
{
    /**
     * @param \EmailSender\MessageLog\Domain\Aggregate\MessageLog     $messageLog
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @throws \EmailSender\MessageQueue\Infrastructure\Service\SMTPException
     * @throws \Exception
     */
    public function send(MessageLog $messageLog, MessageStore $messageStore): void;
}
