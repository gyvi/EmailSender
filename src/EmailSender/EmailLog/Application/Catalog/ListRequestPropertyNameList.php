<?php

namespace EmailSender\EmailLog\Application\Catalog;

/**
 * Class EmailLogListPropertyNames
 *
 * @package EmailSender\EmailLog
 */
class ListRequestPropertyNameList
{
    /**
     * Sender email address.
     */
    const FROM            = 'from';

    /**
     * Count per page.
     */
    const PER_PAGE        = 'perPage';

    /**
     * Requested page number.
     */
    const PAGE            = 'page';

    /**
     * Last emailLogId. Responded email logs will be smaller emailLogId's than this property.
     * (Used for FB like 'more' button).
     */
    const LAST_EMAIL_LOG_ID = 'lastEmailLogId';
}
