<?php

namespace EmailSender\MessageQueue\Domain\Aggregator;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageQueue\Application\Catalog\MessageQueuePropertyNames;
use JsonSerializable;

/**
 * Class MessageQueue
 *
 * @package EmailSender\MessageQueue
 */
class MessageQueue implements JsonSerializable
{
    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $messageLogId;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $messageId;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $delay;

    /**
     * MessageQueue constructor.
     *
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageLogId
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $delay
     */
    public function __construct(UnsignedInteger $messageLogId, UnsignedInteger $messageId, UnsignedInteger $delay)
    {
        $this->messageLogId = $messageLogId;
        $this->messageId    = $messageId;
        $this->delay        = $delay;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function getMessageLogId(): UnsignedInteger
    {
        return $this->messageLogId;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function getMessageId(): UnsignedInteger
    {
        return $this->messageId;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function getDelay(): UnsignedInteger
    {
        return $this->delay;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            MessageQueuePropertyNames::MESSAGE_LOG_ID => $this->getMessageLogId(),
            MessageQueuePropertyNames::MESSAGE_ID => $this->getMessageId(),
            MessageQueuePropertyNames::DELAY => $this->getDelay(),
        ];
    }
}
