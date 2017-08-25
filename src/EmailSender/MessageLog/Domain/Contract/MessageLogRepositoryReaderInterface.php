<?php

namespace EmailSender\MessageLog\Domain\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageLog\Domain\Entity\ListMessageLogsRequest;

/**
 * Interface MessageLogRepositoryReaderInterface
 *
 * @package EmailSender\MessageLog
 */
interface MessageLogRepositoryReaderInterface
{
    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageLogId
     *
     * @return array
     */
    public function readByMessageLogId(UnsignedInteger $messageLogId): array;

    /**
     * @param \EmailSender\MessageLog\Domain\Entity\ListMessageLogsRequest $listMessageLogsRequest
     *
     * @return array
     */
    public function listMessageLogs(ListMessageLogsRequest $listMessageLogsRequest): array;
}
