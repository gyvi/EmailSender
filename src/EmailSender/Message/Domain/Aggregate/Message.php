<?php

namespace EmailSender\Message\Domain\Aggregate;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MailAddress\Application\Collection\MailAddressCollection;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\Core\ValueObject\Subject;
use EmailSender\Message\Domain\ValueObject\Body;
use JsonSerializable;

/**
 * Class Message
 *
 * @package EmailSender\Message
 */
class Message implements JsonSerializable
{
    /**
     * @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress
     */
    private $from;

    /**
     * @var \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    private $to;

    /**
     * @var \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    private $cc;

    /**
     * @var \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    private $bcc;

    /**
     * @var \EmailSender\Core\ValueObject\Subject
     */
    private $subject;

    /**
     * @var \EmailSender\Message\Domain\ValueObject\Body
     */
    private $body;

    /**
     * @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress|null
     */
    private $replyTo;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    private $delay;

    /**
     * Message constructor.
     *
     * @param \EmailSender\MailAddress\Domain\Aggregate\MailAddress                    $from
     * @param \EmailSender\MailAddress\Application\Collection\MailAddressCollection    $to
     * @param \EmailSender\MailAddress\Application\Collection\MailAddressCollection    $cc
     * @param \EmailSender\MailAddress\Application\Collection\MailAddressCollection    $bcc
     * @param \EmailSender\Core\ValueObject\Subject                                    $subject
     * @param \EmailSender\Message\Domain\ValueObject\Body                             $body
     * @param \EmailSender\MailAddress\Domain\Aggregate\MailAddress|null               $replyTo
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $delay
     */
    public function __construct(
        MailAddress $from,
        MailAddressCollection $to,
        MailAddressCollection $cc,
        MailAddressCollection $bcc,
        Subject $subject,
        Body $body,
        ?MailAddress $replyTo,
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
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress
     */
    public function getFrom(): MailAddress
    {
        return $this->from;
    }

    /**
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getTo(): MailAddressCollection
    {
        return $this->to;
    }

    /**
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getCc(): MailAddressCollection
    {
        return $this->cc;
    }

    /**
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getBcc(): MailAddressCollection
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
     * @return \EmailSender\Message\Domain\ValueObject\Body
     */
    public function getBody(): Body
    {
        return $this->body;
    }

    /**
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress|null
     */
    public function getReplyTo(): ?MailAddress
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

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
