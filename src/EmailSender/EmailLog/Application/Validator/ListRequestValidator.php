<?php

namespace EmailSender\EmailLog\Application\Validator;

use EmailSender\Core\Validator\RequestValidator;
use EmailSender\EmailLog\Application\Catalog\ListRequestPropertyNameList;

/**
 * Class EmailLogListRequestValidator
 *
 * @package EmailSender\EmailLog
 */
class ListRequestValidator extends RequestValidator
{
    /**
     * @var array
     */
    protected $optionalProperties = [
        ListRequestPropertyNameList::FROM,
        ListRequestPropertyNameList::PER_PAGE,
        ListRequestPropertyNameList::PAGE,
        ListRequestPropertyNameList::LAST_EMAIL_LOG_ID,
    ];
}
