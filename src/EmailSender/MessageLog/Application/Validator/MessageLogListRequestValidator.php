<?php

namespace EmailSender\MessageLog\Application\Validator;

use EmailSender\Core\Validator\RequestValidator;
use EmailSender\MessageLog\Application\Catalog\ListMessageLogsRequestPropertyNames;

/**
 * Class MessageLogListRequestValidator
 *
 * @package EmailSender\MessageLog
 */
class MessageLogListRequestValidator extends RequestValidator
{
    /**
     * @var array
     */
    protected $requiredProperties = [
    ];

    /**
     * @var array
     */
    protected $optionalProperties = [
        ListMessageLogsRequestPropertyNames::FROM,
        ListMessageLogsRequestPropertyNames::ROWS,
        ListMessageLogsRequestPropertyNames::PAGE,
        ListMessageLogsRequestPropertyNames::LAST_MESSAGE_ID,
    ];
}
