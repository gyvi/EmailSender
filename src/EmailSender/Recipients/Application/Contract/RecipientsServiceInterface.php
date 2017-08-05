<?php

namespace EmailSender\Recipients\Application\Contract;

use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Recipients\Domain\Aggregate\Recipients;

/**
 * Interface RecipientsService
 *
 * @package EmailSender\Recipients
 */
interface RecipientsService
{
    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function getRecipientsFromMessage(Message $message): Recipients;
}
