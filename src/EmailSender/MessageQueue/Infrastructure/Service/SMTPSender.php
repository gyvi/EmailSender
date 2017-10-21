<?php

namespace EmailSender\MessageQueue\Infrastructure\Service;

use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageQueue\Domain\Contract\SMTPSenderInterface;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use Closure;
use SMTP;

/**
 * Class SMTPSender
 *
 * @package EmailSender\MessageQueue
 */
class SMTPSender implements SMTPSenderInterface
{
    /**
     * @var \Closure
     */
    private $smtpService;

    /**
     * SMTPSender constructor.
     *
     * @param \Closure $smtpService
     */
    public function __construct(Closure $smtpService)
    {
        $this->smtpService = $smtpService;
    }

    /**
     * @param \EmailSender\MessageLog\Domain\Aggregate\MessageLog     $messageLog
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @throws \EmailSender\MessageQueue\Infrastructure\Service\SMTPException
     * @throws \Exception
     */
    public function send(MessageLog $messageLog, MessageStore $messageStore): void
    {
        /** @var SMTP $smtp */
        $smtp = ($this->smtpService)();

        if (!$smtp->mail($messageLog->getFrom()->getAddress()->getValue())) {
            throw new SMTPException('Unable to set SMTP From: ' . $messageLog->getFrom()->getAddress()->getValue());
        }

        $this->setRecipients($messageStore, $smtp);

        if (!$smtp->data($messageStore->getMessage()->getValue())) {
            throw new SMTPException('Unable to set SMTP body.');
        }

        $smtp->quit();
        $smtp->close();
    }

    /**
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     * @param \SMTP                                                   $smtp
     *
     * @throws \Exception
     */
    private function setRecipients(MessageStore $messageStore, SMTP $smtp): void
    {
        /** @var \EmailSender\Core\ValueObject\EmailAddress $to */
        foreach ($messageStore->getRecipients()->getTo() as $to) {
            $this->addRecipient($smtp, $to->getAddress()->getValue());
        }

        /** @var \EmailSender\Core\ValueObject\EmailAddress $cc */
        foreach ($messageStore->getRecipients()->getCc() as $cc) {
            $this->addRecipient($smtp, $cc->getAddress()->getValue());
        }

        /** @var \EmailSender\Core\ValueObject\EmailAddress $bcc */
        foreach ($messageStore->getRecipients()->getBcc() as $bcc) {
            $this->addRecipient($smtp, $bcc->getAddress()->getValue());
        }
    }

    /**
     * @param \SMTP  $smtp
     * @param string $recipient
     *
     * @throws \Exception
     */
    private function addRecipient(SMTP $smtp, string $recipient)
    {
        if (!$smtp->recipient($recipient)) {
            throw new SMTPException('Unable to set SMTP recipient: ' . $recipient);
        }
    }
}
