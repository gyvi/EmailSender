<?php

namespace EmailSender\MessageLog\Domain\Contract;

use EmailSender\MessageLog\Domain\Aggregator\MessageLog;

/**
 * Interface MessageLogRepositoryWriterInterface
 *
 * @package EmailSender\MessageLog
 */
interface MessageLogRepositoryWriterInterface
{
    /**
     * @param \EmailSender\MessageLog\Domain\Aggregator\MessageLog $messageLog
     *
     * @return int
     */
    public function add(MessageLog $messageLog): int;
}
