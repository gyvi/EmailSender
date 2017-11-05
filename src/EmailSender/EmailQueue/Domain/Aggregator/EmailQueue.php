<?php

namespace EmailSender\EmailQueue\Domain\Aggregator;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\EmailQueue\Application\Catalog\EmailQueuePropertyNames;
use JsonSerializable;

/**
 * Class EmailQueue
 *
 * @package EmailSender\EmailQueue
 */
class EmailQueue implements JsonSerializable
{
    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $emailLogId;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $composedEmailId;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $delay;

    /**
     * EmailQueue constructor.
     *
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $emailLogId
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $delay
     */
    public function __construct(UnsignedInteger $emailLogId, UnsignedInteger $composedEmailId, UnsignedInteger $delay)
    {
        $this->emailLogId      = $emailLogId;
        $this->composedEmailId = $composedEmailId;
        $this->delay           = $delay;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function getEmailLogId(): UnsignedInteger
    {
        return $this->emailLogId;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function getComposedEmailId(): UnsignedInteger
    {
        return $this->composedEmailId;
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
    public function jsonSerialize(): array
    {
        return [
            EmailQueuePropertyNames::EMAIL_LOG_ID      => $this->getEmailLogId(),
            EmailQueuePropertyNames::COMPOSED_EMAIL_ID => $this->getComposedEmailId(),
            EmailQueuePropertyNames::DELAY             => $this->getDelay(),
        ];
    }
}
