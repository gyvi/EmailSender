<?php

namespace EmailSender\MessageLog\Application\ValueObject;

use EmailSender\Core\Scalar\Application\ValueObject\Enum\Enum;
use EmailSender\MessageLog\Application\Catalog\MessageLogStatuses;

class MessageLogStatus extends Enum
{
    /**
     * List of enabled values.
     *
     * @var array
     */
    protected $enabledValues = [
        MessageLogStatuses::STATUS_ERROR,
        MessageLogStatuses::STATUS_LOGGED,
        MessageLogStatuses::STATUS_QUEUED,
        MessageLogStatuses::STATUS_SENT,
    ];
}
