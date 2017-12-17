<?php

namespace EmailSender\EmailQueue\Domain\Contract;

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
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue
     */
    public function add(EmailQueue $emailQueue): EmailQueue;
}
