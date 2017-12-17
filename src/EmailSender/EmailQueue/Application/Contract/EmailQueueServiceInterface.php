<?php

namespace EmailSender\EmailQueue\Application\Contract;

use EmailSender\Core\ValueObject\EmailStatus;
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
     * @return \EmailSender\Core\ValueObject\EmailStatus
     *
     * @throws \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     */
    public function add(EmailLog $emailLog): EmailStatus;

    /**
     * @param array $emailQueueArray
     *
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue
     *
     * @throws \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     */
    public function get(array $emailQueueArray): EmailQueue;
}
