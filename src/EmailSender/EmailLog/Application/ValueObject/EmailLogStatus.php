<?php

namespace EmailSender\EmailLog\Application\ValueObject;

use EmailSender\Core\Scalar\Application\ValueObject\Enum\Enum;
use EmailSender\EmailLog\Application\Catalog\EmailLogStatuses;

class EmailLogStatus extends Enum
{
    /**
     * List of enabled values.
     *
     * @var array
     */
    protected $enabledValues = [
        EmailLogStatuses::STATUS_ERROR,
        EmailLogStatuses::STATUS_LOGGED,
        EmailLogStatuses::STATUS_QUEUED,
        EmailLogStatuses::STATUS_SENT,
    ];
}
