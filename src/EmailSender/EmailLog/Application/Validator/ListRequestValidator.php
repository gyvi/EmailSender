<?php

namespace EmailSender\EmailLog\Application\Validator;

use EmailSender\Core\Validator\RequestValidator;
use EmailSender\EmailLog\Application\Catalog\ListRequestPropertyNames;

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
        ListRequestPropertyNames::FROM,
        ListRequestPropertyNames::PER_PAGE,
        ListRequestPropertyNames::PAGE,
        ListRequestPropertyNames::LAST_EMAIL_LOG_ID,
    ];
}
