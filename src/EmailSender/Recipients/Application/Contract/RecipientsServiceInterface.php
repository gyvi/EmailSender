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
     * @param array $recipientsArray
     *
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function getRecipientsFromArray(array $recipientsArray): Recipients;
}
