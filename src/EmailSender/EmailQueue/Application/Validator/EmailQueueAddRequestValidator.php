<?php

namespace EmailSender\EmailQueue\Application\Validator;

use EmailSender\Core\Validator\RequestValidator;
use EmailSender\Email\Application\Catalog\EmailPropertyNameList;

/**
 * Class EmailQueueAddRequestValidator
 *
 * @package EmailSender\EmailQueue
 */
class EmailQueueAddRequestValidator extends RequestValidator
{
    /**
     * @var array
     */
    protected $requiredProperties = [
        EmailPropertyNameList::FROM,
        EmailPropertyNameList::TO,
        EmailPropertyNameList::SUBJECT,
        EmailPropertyNameList::BODY,
    ];

    /**
     * @var array
     */
    protected $optionalProperties = [
        EmailPropertyNameList::CC,
        EmailPropertyNameList::BCC,
        EmailPropertyNameList::DELAY,
        EmailPropertyNameList::REPLY_TO,
    ];
}
