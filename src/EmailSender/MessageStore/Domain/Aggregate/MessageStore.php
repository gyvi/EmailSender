<?php

namespace EmailSender\MessageStore\Domain\Aggregate;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Core\Entity\Recipients;
use JsonSerializable;

/**
 * Class MessageStore
 *
 * @package EmailSender\MessageStore
 */
class MessageStore implements JsonSerializable
{
    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $messageId;

    /**
     * @var \EmailSender\Core\Entity\Recipients
     */
    private $recipients;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral
     */
    private $message;

    /**
     * MessageStore constructor.
     *
     * @param \EmailSender\Core\Entity\Recipients                                   $recipients
     * @param \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral $message
     */
    public function __construct(Recipients $recipients, StringLiteral $message)
    {
        $this->recipients = $recipients;
        $this->message    = $message;
    }

    /**
     * @return \EmailSender\Core\Entity\Recipients
     */
    public function getRecipients(): Recipients
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

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
