<?php

namespace EmailSender\MessageLog\Domain\Contract;

use EmailSender\MessageLog\Domain\Aggregate\MessageLog;

/**
 * Interface MessageLogRepositoryWriterInterface
 *
 * @package EmailSender\MessageLog
 */
interface MessageLogRepositoryWriterInterface
{
    /**
     * @param \EmailSender\MessageLog\Domain\Aggregate\MessageLog $messageLog
     *
     * @return int
     */
    public function add(MessageLog $messageLog): int;
}
