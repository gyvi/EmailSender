<?php

namespace EmailSender\EmailLog\Domain\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\EmailLog\Domain\Entity\ListRequest;

/**
 * Interface EmailLogRepositoryReaderInterface
 *
 * @package EmailSender\EmailLog
 */
interface EmailLogRepositoryReaderInterface
{
    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $emailLogId
     *
     * @return array
     */
    public function get(UnsignedInteger $emailLogId): array;

    /**
     * @param \EmailSender\EmailLog\Domain\Entity\ListRequest $listRequest
     *
     * @return array
     */
    public function list(ListRequest $listRequest): array;
}
