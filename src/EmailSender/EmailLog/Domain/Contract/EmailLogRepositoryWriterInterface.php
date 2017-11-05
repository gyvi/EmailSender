<?php

namespace EmailSender\EmailLog\Domain\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailStatus;

/**
 * Interface EmailLogRepositoryWriterInterface
 *
 * @package EmailSender\EmailLog
 */
interface EmailLogRepositoryWriterInterface
{
    /**
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog $emailLog
     *
     * @return int
     */
    public function add(EmailLog $emailLog): int;

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $emailLogId
     * @param \EmailSender\Core\ValueObject\EmailStatus                                $emailLogStatus
     * @param \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral    $errorMessage
     */
    public function setStatus(
        UnsignedInteger $emailLogId,
        EmailStatus $emailLogStatus,
        StringLiteral $errorMessage
    ): void;
}
