<?php

namespace EmailSender\EmailLog\Domain\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\EmailLog\Application\Collection\EmailLogCollection;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
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
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     */
    public function get(UnsignedInteger $emailLogId): EmailLog;

    /**
     * @param \EmailSender\EmailLog\Domain\Entity\ListRequest $listRequest
     *
     * @return \EmailSender\EmailLog\Application\Collection\EmailLogCollection
     */
    public function list(ListRequest $listRequest): EmailLogCollection;
}
