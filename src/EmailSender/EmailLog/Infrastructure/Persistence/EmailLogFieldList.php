<?php

namespace EmailSender\EmailLog\Infrastructure\Persistence;

/**
 * Class EmailLogFieldList
 *
 * @package EmailSender\EmailLog
 */
class EmailLogFieldList
{
    /**
     * Primary key of the emailLog SQL table.
     */
    const EMAIL_LOG_ID = 'emailLogId';

    /**
     * Primary key of the composedEmail SQL table.
     */
    const COMPOSED_EMAIL_ID = 'composedEmailId';

    /**
     * From field of the emailLog SQL table.
     */
    const FROM = 'from';

    /**
     * Recipients field of the emailLog SQL table.
     */
    const RECIPIENTS = 'recipients';

    /**
     * Subject field of the emailLog SQL table.
     */
    const SUBJECT = 'subject';

    /**
     * Logged field of the emailLog SQL table.
     */
    const LOGGED = 'logged';

    /**
     * Queued field of the emailLog SQL table.
     */
    const QUEUED = 'queued';

    /**
     * Sent field of the emailLog SQL table.
     */
    const SENT = 'sent';

    /**
     * Delay field of the emailLog SQL table.
     */
    const DELAY = 'delay';

    /**
     * Status field of the emailLog SQL table.
     */
    const STATUS = 'status';

    /**
     * Error message field of the emailLog SQL table.
     */
    const ERROR_MESSAGE = 'errorMessage';
}
