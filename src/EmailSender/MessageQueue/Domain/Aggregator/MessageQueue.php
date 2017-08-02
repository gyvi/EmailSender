<?php

namespace EmailSender\MessageQueue\Domain\Aggregator;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

/**
 * Class MessageQueue
 *
 * @package EmailSender\MessageQueue
 */
class MessageQueue
{
    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $logId;

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
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $logId
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $delay
     */
    public function __construct(UnsignedInteger $logId, UnsignedInteger $messageId, UnsignedInteger $delay)
    {
        $this->logId     = $logId;
        $this->messageId = $messageId;
        $this->delay     = $delay;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function getLogId(): UnsignedInteger
    {
        return $this->logId;
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
}
