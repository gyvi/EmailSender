<?php

namespace EmailSender\Email\Domain\Aggregate;

use EmailSender\Core\Collection\EmailAddressCollection;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailAddress;
use EmailSender\Core\ValueObject\Subject;
use EmailSender\Email\Domain\ValueObject\Body;

/**
 * Class Email
 *
 * @package EmailSender\Email
 */
class Email
{
    /**
     * @var \EmailSender\Core\ValueObject\EmailAddress
     */
    private $from;

    /**
     * @var \EmailSender\Core\Collection\EmailAddressCollection
     */
    private $to;

    /**
     * @var \EmailSender\Core\Collection\EmailAddressCollection
     */
    private $cc;

    /**
     * @var \EmailSender\Core\Collection\EmailAddressCollection
     */
    private $bcc;

    /**
     * @var \EmailSender\Core\ValueObject\Subject
     */
    private $subject;

    /**
     * @var \EmailSender\Email\Domain\ValueObject\Body
     */
    private $body;

    /**
     * @var \EmailSender\Core\ValueObject\EmailAddress|null
     */
    private $replyTo;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $delay;

    /**
     * Email constructor.
     *
     * @param \EmailSender\Core\ValueObject\EmailAddress                               $from
     * @param \EmailSender\Core\Collection\EmailAddressCollection                      $to
     * @param \EmailSender\Core\Collection\EmailAddressCollection                      $cc
     * @param \EmailSender\Core\Collection\EmailAddressCollection                      $bcc
     * @param \EmailSender\Core\ValueObject\Subject                                    $subject
     * @param \EmailSender\Email\Domain\ValueObject\Body                             $body
     * @param \EmailSender\Core\ValueObject\EmailAddress|null                          $replyTo
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $delay
     */
    public function __construct(
        EmailAddress $from,
        EmailAddressCollection $to,
        EmailAddressCollection $cc,
        EmailAddressCollection $bcc,
        Subject $subject,
        Body $body,
        ?EmailAddress $replyTo,
        UnsignedInteger $delay
    ) {
        $this->from    = $from;
        $this->to      = $to;
        $this->cc      = $cc;
        $this->bcc     = $bcc;
        $this->subject = $subject;
        $this->body    = $body;
        $this->replyTo = $replyTo;
        $this->delay   = $delay;
    }

    /**
     * @return \EmailSender\Core\ValueObject\EmailAddress
     */
    public function getFrom(): EmailAddress
    {
        return $this->from;
    }

    /**
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     */
    public function getTo(): EmailAddressCollection
    {
        return $this->to;
    }

    /**
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     */
    public function getCc(): EmailAddressCollection
    {
        return $this->cc;
    }

    /**
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     */
    public function getBcc(): EmailAddressCollection
    {
        return $this->bcc;
    }

    /**
     * @return \EmailSender\Core\ValueObject\Subject
     */
    public function getSubject(): Subject
    {
        return $this->subject;
    }

    /**
     * @return \EmailSender\Email\Domain\ValueObject\Body
     */
    public function getBody(): Body
    {
        return $this->body;
    }

    /**
     * @return \EmailSender\Core\ValueObject\EmailAddress|null
     */
    public function getReplyTo(): ?EmailAddress
    {
        return $this->replyTo;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function getDelay(): UnsignedInteger
    {
        return $this->delay;
    }
}
