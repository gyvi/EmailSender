<?php

namespace EmailSender\ComposedEmail\Infrastructure\Persistence;

/**
 * Class ComposedEmailRepositoryFieldList
 *
 * @package EmailSender\ComposedEmail
 */
class ComposedEmailRepositoryFieldList
{
    /**
     * Primary key of the composedEmail SQL table.
     */
    const COMPOSED_EMAIL_ID = 'composedEmailId';

    /**
     * From field of the composedEmail SQL table.
     */
    const FROM = 'from';

    /**
     * Recipients field of the composedEmail SQL table.
     */
    const RECIPIENTS = 'recipients';

    /**
     * Email field of the composedEmail SQL table.
     */
    const EMAIL = 'email';
}
