<?php

namespace EmailSender\EmailLog\Application\Catalog;

/**
 * Class EmailLogStatuses
 *
 * @package EmailSender\EmailLog
 */
class EmailLogStatuses
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
