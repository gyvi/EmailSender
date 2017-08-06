<?php

namespace EmailSender\MessageStore\Infrastructure\Persistence;

/**
 * Class MessageStoreFieldList
 *
 * @package EmailSender\MessageStore
 */
class MessageStoreFieldList
{
    /**
     * Primary key of the messageStore SQL table.
     */
    const FIELD_MESSAGE_ID = 'messageId';

    /**
     * Recipients field of the messageStore SQL table.
     */
    const FIELD_RECIPIENTS = 'recipients';

    /**
     * Message field of the messageStore SQL table.
     */
    const FIELD_MESSAGE = 'message';
}
