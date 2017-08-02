<?php

namespace EmailSender\MessageStore\Domain\Aggregate;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\MailAddress\Application\Collection\MailAddressCollection;

/**
 * Class MessageStore
 *
 * @package EmailSender\MessageStore
 */
class MessageStore
{
    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $messageId;

    /**
     * @var \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    private $recipients;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral
     */
    private $message;

    /**
     * MessageStore constructor.
     *
     * @param \EmailSender\MailAddress\Application\Collection\MailAddressCollection $recipients
     * @param \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral $message
     */
    public function __construct(MailAddressCollection $recipients, StringLiteral $message)
    {
        $this->recipients = $recipients;
        $this->message    = $message;
    }

    /**
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getRecipients(): MailAddressCollection
    {
        return $this->recipients;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral
     */
    public function getMessage(): StringLiteral
    {
        return $this->message;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function getMessageId(): UnsignedInteger
    {
        return $this->messageId;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     */
    public function setMessageId(UnsignedInteger $messageId)
    {
        $this->messageId = $messageId;
    }
}
