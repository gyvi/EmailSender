<?php

namespace EmailSender\EmailLog\Application\Collection;

use EmailSender\Core\Collection\Collection;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;

/**
 * Class EmailLogCollection
 *
 * @package EmailSender\EmailLog
 */
class EmailLogCollection extends Collection
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return EmailLog::class;
    }
}
