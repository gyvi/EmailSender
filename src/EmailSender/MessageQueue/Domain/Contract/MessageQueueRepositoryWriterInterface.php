<?php

namespace EmailSender\MessageQueue\Domain\Contract;

use EmailSender\MessageQueue\Domain\Aggregator\MessageQueue;

/**
 * Interface MessageQueueRepositoryWriterInterface
 *
 * @package EmailSender\MessageQueue
 */
interface MessageQueueRepositoryWriterInterface
{
    /**
     * @param \EmailSender\MessageQueue\Domain\Aggregator\MessageQueue $messageQueue
     *
     * @return bool
     */
    public function add(MessageQueue $messageQueue): bool;
}
