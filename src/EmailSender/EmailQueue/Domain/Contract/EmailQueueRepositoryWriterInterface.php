<?php

namespace EmailSender\EmailQueue\Domain\Contract;

use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;

/**
 * Interface EmailQueueRepositoryWriterInterface
 *
 * @package EmailSender\EmailQueue
 */
interface EmailQueueRepositoryWriterInterface
{
    /**
     * @param \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue $emailQueue
     *
     * @return \EmailSender\Core\ValueObject\EmailStatus
     */
    public function add(EmailQueue $emailQueue): EmailStatus;
}
