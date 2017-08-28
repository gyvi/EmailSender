<?php

namespace EmailSender\MessageLog\Domain\Aggregate;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\Core\ValueObject\Subject;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\SignedInteger;
use EmailSender\Recipients\Domain\Aggregate\Recipients;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use JsonSerializable;

/**
 * Class MessageLog
 *
 * @package EmailSender\MessageLog
 */
class MessageLog implements JsonSerializable
{
    /**
     * @var UnsignedInteger
     */
    private $messageLogId;

    /**
     * @var UnsignedInteger
     */
    private $messageId;

    /**
     * @var MailAddress
     */
    private $from;

    /**
     * @var \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    private $recipients;

    /**
     * @var Subject
     */
    private $subject;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime
     */
    private $logged;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime
     */
    private $queued;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime
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

    /**
     * MessageLog constructor.
     *
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     * @param \EmailSender\MailAddress\Domain\Aggregate\MailAddress                    $from
     * @param \EmailSender\Recipients\Domain\Aggregate\Recipients                      $recipients
     * @param \EmailSender\Core\ValueObject\Subject                                    $subject
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $delay
     */
    public function __construct(
        UnsignedInteger $messageId,
        MailAddress $from,
        Recipients $recipients,
        Subject $subject,
        UnsignedInteger $delay
    ) {
        $this->messageId  = $messageId;
        $this->from       = $from;
        $this->recipients = $recipients;
        $this->subject    = $subject;
        $this->delay      = $delay;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function getMessageLogId(): UnsignedInteger
    {
        return $this->messageLogId;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageLogId
     */
    public function setMessageLogId(UnsignedInteger $messageLogId)
    {
        $this->messageLogId = $messageLogId;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function getMessageId(): UnsignedInteger
    {
        return $this->messageId;
    }

    /**
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress
     */
    public function getFrom(): MailAddress
    {
        return $this->from;
    }

    /**
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function getRecipients(): Recipients
    {
        return $this->recipients;
    }

    /**
     * @return \EmailSender\Core\ValueObject\Subject
     */
    public function getSubject(): Subject
    {
        return $this->subject;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime
     */
    public function getLogged(): DateTime
    {
        return $this->logged;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime $logged
     */
    public function setLogged(DateTime $logged)
    {
        $this->logged = $logged;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime
     */
    public function getQueued(): DateTime
    {
        return $this->queued;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime $queued
     */
    public function setQueued(DateTime $queued)
    {
        $this->queued = $queued;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime
     */
    public function getSent(): DateTime
    {
        return $this->sent;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime $sent
     */
    public function setSent(DateTime $sent)
    {
        $this->sent = $sent;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function getDelay(): UnsignedInteger
    {
        return $this->delay;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\SignedInteger
     */
    public function getStatus(): SignedInteger
    {
        return $this->status;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\SignedInteger $status
     */
    public function setStatus(SignedInteger $status)
    {
        $this->status = $status;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral
     */
    public function getErrorMessage(): StringLiteral
    {
        return $this->errorMessage;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral $errorMessage
     */
    public function setErrorMessage(StringLiteral $errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
