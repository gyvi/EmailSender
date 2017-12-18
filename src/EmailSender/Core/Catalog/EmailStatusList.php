<?php

namespace EmailSender\Core\Catalog;

/**
 * Class EmailLogStatusList
 *
 * @package EmailSender\Core
 */
class EmailStatusList
{
    /**
     * An error happened.
     *
     * @var int
     */
    const ERROR  = '-1';

    /**
     * Message is saved, and logged.
     *
     * @var int
     */
    const LOGGED = '0';

    /**
     * Message sent to the queue.
     *
     * @var int
     */
    const QUEUED = '1';

    /**
     * Message sent.
     *
     * @var int
     */
    const SENT   = '2';
}
