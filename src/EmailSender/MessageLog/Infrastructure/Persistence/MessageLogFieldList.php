<?php

namespace EmailSender\MessageLog\Infrastructure\Persistence;

/**
 * Class MessageLogFieldList
 *
 * @package EmailSender\MessageLog
 */
class MessageLogFieldList
{
    /**
     * Primary key of the messageLog SQL table.
     */
    const MESSAGE_LOG_ID = 'messageLogId';

    /**
     * Primary key of the messageStore SQL table. Connected Message id.
     */
    const MESSAGE_ID = 'messageId';

    /**
     * From field of the messageLog SQL table.
     */
    const FROM = 'from';

    /**
     * Recipients field of the messageLog SQL table.
     */
    const RECIPIENTS = 'recipients';

    /**
     * Subject field of the messageLog SQL table.
     */
    const SUBJECT = 'subject';

    /**
     * Logged field of the messageLog SQL table.
     */
    const LOGGED = 'logged';

    /**
     * Queued field of the messageLog SQL table.
     */
    const QUEUED = 'queued';

    /**
     * Sent field of the messageLog SQL table.
     */
    const SENT = 'sent';

    /**
     * Delay field of the messageLog SQL table.
     */
    const DELAY = 'delay';

    /**
     * Status field of the messageLog SQL table.
     */
    const STATUS = 'status';

    /**
     * Error message field of the messageLog SQL table.
     */
    const ERROR_MESSAGE = 'errorMessage';
}
