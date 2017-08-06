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
    const FIELD_MESSAGE_LOG_ID = 'messageLogId';

    /**
     * Primary key of the messageStore SQL table. Connected Message id.
     */
    const FIELD_MESSAGE_ID = 'messageId';

    /**
     * From field of the messageLog SQL table.
     */
    const FIELD_FROM = 'from';

    /**
     * Recipients field of the messageLog SQL table.
     */
    const FIELD_RECIPIENTS = 'recipients';

    /**
     * Subject field of the messageLog SQL table.
     */
    const FIELD_SUBJECT = 'subject';

    /**
     * Queued field of the messageLog SQL table.
     */
    const FIELD_QUEUED = 'queued';

    /**
     * Sent field of the messageLog SQL table.
     */
    const FIELD_SENT = 'sent';

    /**
     * Delay field of the messageLog SQL table.
     */
    const FIELD_DELAY = 'delay';

    /**
     * Status field of the messageLog SQL table.
     */
    const FIELD_STATUS = 'status';

    /**
     * Error message field of the messageLog SQL table.
     */
    const FIELD_ERROR_MESSAGE = 'errorMessage';
}
