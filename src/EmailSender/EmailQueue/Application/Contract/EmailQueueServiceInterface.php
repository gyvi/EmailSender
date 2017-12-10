<?php

namespace EmailSender\EmailQueue\Application\Contract;

use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;

/**
 * Interface EmailQueueServiceInterface
 *
 * @package EmailSender\EmailQueue
 */
interface EmailQueueServiceInterface
{
    /**
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog $emailLog
     *
     * @return \EmailSender\Core\ValueObject\EmailStatus
     *
     * @throws \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     */
    public function add(EmailLog $emailLog): EmailStatus;
}
