<?php

namespace EmailSender\Email\Application\Catalog;

/**
 * Class EmailPropertyNameList
 *
 * @package EmailSender\Email
 */
class EmailPropertyNameList
{
    /**
     * Email From property name.
     */
    const FROM     = 'from';

    /**
     * Email To property name.
     */
    const TO       = 'to';

    /**
     * Email Cc property name.
     */
    const CC       = 'cc';

    /**
     * Email Bcc property name.
     */
    const BCC      = 'bcc';

    /**
     * Email Subject property name.
     */
    const SUBJECT  = 'subject';

    /**
     * Email Body property name.
     */
    const BODY     = 'body';

    /**
     * Email ReplyTo property name.
     */
    const REPLY_TO = 'replyTo';

    /**
     * Email Delay property name.
     */
    const DELAY    = 'delay';
}
