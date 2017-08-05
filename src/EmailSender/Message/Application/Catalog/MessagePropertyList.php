<?php

namespace EmailSender\Message\Application\Catalog;

/**
 * Class MessagePropertyList
 *
 * @package EmailSender\Message
 */
class MessagePropertyList
{
    /**
     * Message From property name.
     */
    const FROM     = 'from';

    /**
     * Message To property name.
     */
    const TO       = 'to';

    /**
     * Message Cc property name.
     */
    const CC       = 'cc';

    /**
     * Message Bcc property name.
     */
    const BCC      = 'bcc';

    /**
     * Message Subject property name.
     */
    const SUBJECT  = 'subject';

    /**
     * Message Body property name.
     */
    const BODY     = 'body';

    /**
     * Message ReplyTo property name.
     */
    const REPLY_TO = 'replyTo';

    /**
     * Message Delay property name.
     */
    const DELAY    = 'delay';
}
