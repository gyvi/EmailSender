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
        EmailStatusList::STATUS_ERROR,
        EmailStatusList::STATUS_LOGGED,
        EmailStatusList::STATUS_QUEUED,
        EmailStatusList::STATUS_SENT,
    ];
}
