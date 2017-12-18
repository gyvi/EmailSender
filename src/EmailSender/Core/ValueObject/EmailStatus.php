<?php

namespace EmailSender\Core\ValueObject;

use EmailSender\Core\Scalar\Application\ValueObject\Enum\Enum;
use EmailSender\Core\Catalog\EmailStatusList;

class EmailStatus extends Enum
{
    /**
     * List of enabled values.
     *
     * @var array
     */
    protected $enabledValues = [
        EmailStatusList::ERROR,
        EmailStatusList::LOGGED,
        EmailStatusList::QUEUED,
        EmailStatusList::SENT,
    ];
}
