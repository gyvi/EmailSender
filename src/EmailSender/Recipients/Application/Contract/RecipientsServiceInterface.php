<?php

namespace EmailSender\Recipients\Application\Contract;

use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Recipients\Domain\Aggregate\Recipients;

/**
 * Interface RecipientsServiceInterface
 *
 * @package EmailSender\Recipients
 */
interface RecipientsServiceInterface
{
    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function getRecipientsFromMessage(Message $message): Recipients;

    /**
     * @param string $recipients
     *
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function getRecipientsFromJson(string $recipients): Recipients;
}
