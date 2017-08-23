<?php

namespace EmailSender\MessageLog\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryWriterInterface;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageLog\Application\ValueObject\MessageLogStatus;

/**
 * Class UpdateMessageLogService
 *
 * @package EmailSender\MessageLog\Domain\Service
 */
class UpdateMessageLogService
{
    /**
     * @var \EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryWriterInterface
     */
    private $repositoryWriter;

    /**
     * UpdateMessageLogService constructor.
     *
     * @param \EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryWriterInterface $repositoryWriter
     */
    public function __construct(MessageLogRepositoryWriterInterface $repositoryWriter)
    {
        $this->repositoryWriter  = $repositoryWriter;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageLogId
     * @param \EmailSender\MessageLog\Application\ValueObject\MessageLogStatus         $messageLogStatus
     * @param \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral    $errorMessage
     */
    public function setStatus(
        UnsignedInteger $messageLogId,
        MessageLogStatus $messageLogStatus,
        StringLiteral $errorMessage
    ): void
    {
        $this->repositoryWriter->setStatus($messageLogId, $messageLogStatus, $errorMessage);
    }
}
