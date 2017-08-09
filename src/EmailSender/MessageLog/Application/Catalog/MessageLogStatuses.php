<?php

namespace EmailSender\MessageLog\Application\Catalog;

/**
 * Class MessageLogStatuses
 *
 * @package EmailSender\MessageLog
 */
class MessageLogStatuses
{
    /**
     * An error happened.
     *
     * @var int
     */
    const STATUS_ERROR  = '-1';

    /**
     * Message is saved, and logged.
     *
     * @var int
     */
    const STATUS_LOGGED = '0';

    /**
     * Message sent to the queue.
     *
     * @var int
     */
    const STATUS_QUEUED = '1';

    /**
     * Message sent.
     *
     * @var int
     */
    const STATUS_SENT   = '2';
}
