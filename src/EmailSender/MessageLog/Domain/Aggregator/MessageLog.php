<?php

namespace EmailSender\MessageLog\Domain\Aggregator;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\Core\ValueObject\Subject;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\SignedInteger;

/**
 * Class MessageLog
 *
 * @package EmailSender\MessageLog
 */
class MessageLog
{
    /**
     * @var UnsignedInteger
     */
    private $logId;

    /**
     * @var UnsignedInteger
     */
    private $messageId;

    /**
     * @var MailAddress
     */
    private $from;

    /**
     * @var array
     */
    private $recipients;

    /**
     * @var Subject
     */
    private $subject;

    /**
     * @var \DateTime
     */
    private $queued;

    /**
     * @var \DateTime
     */
    private $sent;

    /**
     * @var UnsignedInteger
     */
    private $delay;

    /**
     * @var SignedInteger
     */
    private $status;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral
     */
    private $errorMessage;

    public function __construct(
        UnsignedInteger $messageId,
        MailAddress $from,
        array $recipients,
        Subject $subject,
        UnsignedInteger $delay
    ) {

    }
}
