<?php

namespace EmailSender\EmailLog\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryWriterInterface;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\EmailLog\Application\ValueObject\EmailLogStatus;

/**
 * Class UpdateEmailLogService
 *
 * @package EmailSender\EmailLog\Domain\Service
 */
class UpdateEmailLogService
{
    /**
     * @var \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryWriterInterface
     */
    private $repositoryWriter;

    /**
     * UpdateEmailLogService constructor.
     *
     * @param \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryWriterInterface $repositoryWriter
     */
    public function __construct(EmailLogRepositoryWriterInterface $repositoryWriter)
    {
        $this->repositoryWriter  = $repositoryWriter;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $emailLogId
     * @param \EmailSender\EmailLog\Application\ValueObject\EmailLogStatus             $emailLogStatus
     * @param \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral    $errorMessage
     */
    public function setStatus(
        UnsignedInteger $emailLogId,
        EmailLogStatus $emailLogStatus,
        StringLiteral $errorMessage
    ): void {
        $this->repositoryWriter->setStatus($emailLogId, $emailLogStatus, $errorMessage);
    }
}
