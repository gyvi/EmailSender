<?php

namespace EmailSender\MessageLog\Domain\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageLog\Application\ValueObject\MessageLogStatus;

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

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageLogId
     * @param \EmailSender\MessageLog\Application\ValueObject\MessageLogStatus         $messageLogStatus
     * @param \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral    $errorMessage
     */
    public function setStatus(
        UnsignedInteger $messageLogId,
        MessageLogStatus $messageLogStatus,
        StringLiteral $errorMessage
    ): void;
}
