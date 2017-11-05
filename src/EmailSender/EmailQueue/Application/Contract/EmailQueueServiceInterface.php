<?php

namespace EmailSender\EmailQueue\Application\Contract;

use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;

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
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue
     */
    public function add(EmailLog $emailLog): EmailQueue;
}
