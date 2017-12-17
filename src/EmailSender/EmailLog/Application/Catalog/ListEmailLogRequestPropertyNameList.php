<?php

namespace EmailSender\EmailLog\Application\Catalog;

/**
 * Class EmailLogListPropertyNames
 *
 * @package EmailSender\EmailLog
 */
class ListEmailLogRequestPropertyNameList
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
     * Last composedEmailId. Returned email logs will be smaller composedEmailId's than this property.
     */
    const LAST_COMPOSED_EMAIL_ID = 'lastComposedEmailId';
}
