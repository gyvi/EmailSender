<?php

namespace EmailSender\ComposedEmail\Infrastructure\Persistence;

/**
 * Class ComposedEmailFieldList
 *
 * @package EmailSender\ComposedEmail
 */
class ComposedEmailFieldList
{
    /**
     * Primary key of the composedEmail SQL table.
     */
    const COMPOSED_EMAIL_ID = 'composedEmailId';

    /**
     * Recipients field of the composedEmail SQL table.
     */
    const RECIPIENTS = 'recipients';

    /**
     * Email field of the composedEmail SQL table.
     */
    const EMAIL = 'email';
}
