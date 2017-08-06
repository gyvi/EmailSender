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
    const MESSAGE_ID_FIELD = 'messageId';

    /**
     * Recipients field of the messageStore SQL table.
     */
    const RECIPIENTS_FIELD = 'recipients';

    /**
     * Message field of the messageStore SQL table.
     */
    const MESSAGE_FIELD = 'message';
}
