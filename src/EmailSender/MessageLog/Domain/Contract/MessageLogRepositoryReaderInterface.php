<?php

namespace EmailSender\MessageLog\Domain\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

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
}
