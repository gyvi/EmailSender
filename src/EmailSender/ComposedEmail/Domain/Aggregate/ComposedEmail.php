<?php

namespace EmailSender\ComposedEmail\Domain\Aggregate;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Core\Entity\Recipients;
use JsonSerializable;

/**
 * Class ComposedEmail
 *
 * @package EmailSender\ComposedEmail
 */
class ComposedEmail implements JsonSerializable
{
    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $composedEmailId;

    /**
     * @var \EmailSender\Core\Entity\Recipients
     */
    private $recipients;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral
     */
    private $email;

    /**
     * ComposedEmail constructor.
     *
     * @param \EmailSender\Core\Entity\Recipients                                   $recipients
     * @param \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral $email
     */
    public function __construct(Recipients $recipients, StringLiteral $email)
    {
        $this->recipients = $recipients;
        $this->email      = $email;
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
    public function getEmail(): StringLiteral
    {
        return $this->email;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function getComposedEmailId(): UnsignedInteger
    {
        return $this->composedEmailId;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     */
    public function setComposedEmailId(UnsignedInteger $composedEmailId)
    {
        $this->composedEmailId = $composedEmailId;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
