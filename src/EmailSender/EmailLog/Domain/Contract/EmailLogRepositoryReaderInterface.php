<?php

namespace EmailSender\EmailLog\Domain\Contract;

use EmailSender\EmailLog\Application\Collection\EmailLogCollection;
use EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest;

/**
 * Interface EmailLogRepositoryReaderInterface
 *
 * @package EmailSender\EmailLog
 */
interface EmailLogRepositoryReaderInterface
{
    /**
     * @param \EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest $listEmailLogRequest
     *
     * @return \EmailSender\EmailLog\Application\Collection\EmailLogCollection
     */
    public function list(ListEmailLogRequest $listEmailLogRequest): EmailLogCollection;
}
