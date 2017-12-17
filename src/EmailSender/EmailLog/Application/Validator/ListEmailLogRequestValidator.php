<?php

namespace EmailSender\EmailLog\Application\Validator;

use EmailSender\Core\Validator\RequestValidator;
use EmailSender\EmailLog\Application\Catalog\ListEmailLogRequestPropertyNameList;

/**
 * Class ListEmailLogRequestValidator
 *
 * @package EmailSender\EmailLog
 */
class ListEmailLogRequestValidator extends RequestValidator
{
    /**
     * @var array
     */
    protected $optionalProperties = [
        ListEmailLogRequestPropertyNameList::FROM,
        ListEmailLogRequestPropertyNameList::PER_PAGE,
        ListEmailLogRequestPropertyNameList::PAGE,
        ListEmailLogRequestPropertyNameList::LAST_COMPOSED_EMAIL_ID,
    ];
}
