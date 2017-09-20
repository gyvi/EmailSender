<?php

namespace EmailSender\MessageLog\Application\Catalog;

/**
 * Class MessageLogListPropertyNames
 *
 * @package EmailSender\MessageLog
 */
class ListMessageLogsRequestPropertyNames
{
    /**
     * Sender email address.
     */
    const FROM            = 'from';

    /**
     * Rows count.
     */
    const ROWS            = 'rows';

    /**
     * Requested page number.
     */
    const PAGE            = 'page';

    /**
     * Last message id. Responsed messages will be smaller messageId's than this property.
     * (Used for FB like 'more' button).
     */
    const LAST_MESSAGE_ID = 'lastMessageId';
}
