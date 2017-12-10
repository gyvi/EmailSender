<?php

namespace EmailSender\ComposedEmail\Domain\Aggregate;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Core\Entity\Recipients;
use EmailSender\Core\ValueObject\EmailAddress;

/**
 * Class ComposedEmail
 *
 * @package EmailSender\ComposedEmail
 */
class ComposedEmail
{
    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $composedEmailId;

    /**
     * @var \EmailSender\Core\ValueObject\EmailAddress
     */
    private $from;

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
     * @param \EmailSender\Core\ValueObject\EmailAddress                            $from
     * @param \EmailSender\Core\Entity\Recipients                                   $recipients
     * @param \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral $email
     */
    public function __construct(EmailAddress $from, Recipients $recipients, StringLiteral $email)
    {
        $this->from       = $from;
        $this->recipients = $recipients;
        $this->email      = $email;
    }

    /**
     * @return \EmailSender\Core\ValueObject\EmailAddress
     */
    public function getFrom(): EmailAddress
    {
        return $this->from;
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
    public function setComposedEmailId(UnsignedInteger $composedEmailId): void
    {
        $this->composedEmailId = $composedEmailId;
    }
}
