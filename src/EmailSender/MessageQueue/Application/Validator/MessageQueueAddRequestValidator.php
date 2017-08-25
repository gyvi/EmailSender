<?php

namespace EmailSender\MessageQueue\Application\Validator;

use EmailSender\Core\Validator\RequestValidator;
use EmailSender\Message\Application\Catalog\MessagePropertyNames;

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
        MessagePropertyNames::FROM,
        MessagePropertyNames::TO,
        MessagePropertyNames::SUBJECT,
        MessagePropertyNames::BODY,
    ];

    /**
     * @var array
     */
    protected $optionalProperties = [
        MessagePropertyNames::CC,
        MessagePropertyNames::BCC,
        MessagePropertyNames::DELAY,
        MessagePropertyNames::REPLY_TO,
    ];
}
