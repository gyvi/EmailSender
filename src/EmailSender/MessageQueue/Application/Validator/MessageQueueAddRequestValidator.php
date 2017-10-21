<?php

namespace EmailSender\MessageQueue\Application\Validator;

use EmailSender\Core\Validator\RequestValidator;
use EmailSender\Message\Application\Catalog\MessagePropertyNameList;

/**
 * Class MessageQueueAddRequestValidator
 *
 * @package EmailSender\MessageQueue
 */
class MessageQueueAddRequestValidator extends RequestValidator
{
    /**
     * @var array
     */
    protected $requiredProperties = [
        MessagePropertyNameList::FROM,
        MessagePropertyNameList::TO,
        MessagePropertyNameList::SUBJECT,
        MessagePropertyNameList::BODY,
    ];

    /**
     * @var array
     */
    protected $optionalProperties = [
        MessagePropertyNameList::CC,
        MessagePropertyNameList::BCC,
        MessagePropertyNameList::DELAY,
        MessagePropertyNameList::REPLY_TO,
    ];
}
