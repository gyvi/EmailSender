<?php

namespace EmailSender\MessageQueue\Application\Validator;

use EmailSender\Core\Validator\RequestValidator;
use EmailSender\Message\Application\Catalog\MessagePropertyList;

/**
 * Class MessageQueueAddRequestValidator
 *
 * @package EmailSender\MessageQueue
 */
class MessageQueueAddRequestValidator extends RequestValidator
{
    protected $requiredProperties = [
        MessagePropertyList::FROM,
        MessagePropertyList::TO,
        MessagePropertyList::SUBJECT,
        MessagePropertyList::BODY,
    ];

    /**
     * @var array
     */
    protected $optionalProperties = [
        MessagePropertyList::CC,
        MessagePropertyList::BCC,
        MessagePropertyList::DELAY,
        MessagePropertyList::REPLY_TO,
    ];
}
