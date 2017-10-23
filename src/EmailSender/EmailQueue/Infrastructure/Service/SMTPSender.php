<?php

namespace EmailSender\EmailQueue\Infrastructure\Service;

use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailQueue\Domain\Contract\SMTPSenderInterface;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use Closure;
use SMTP;

/**
 * Class SMTPSender
 *
 * @package EmailSender\EmailQueue
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
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog           $emailLog
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @throws \EmailSender\EmailQueue\Infrastructure\Service\SMTPException
     * @throws \Exception
     */
    public function send(EmailLog $emailLog, ComposedEmail $composedEmail): void
    {
        /** @var SMTP $smtp */
        $smtp = ($this->smtpService)();

        if (!$smtp->mail($emailLog->getFrom()->getAddress()->getValue())) {
            throw new SMTPException('Unable to set SMTP From: ' . $emailLog->getFrom()->getAddress()->getValue());
        }

        $this->setRecipients($composedEmail, $smtp);

        if (!$smtp->data($composedEmail->getEmail()->getValue())) {
            throw new SMTPException('Unable to set SMTP body.');
        }

        $smtp->quit();
        $smtp->close();
    }

    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     * @param \SMTP                                                     $smtp
     *
     * @throws \Exception
     */
    private function setRecipients(ComposedEmail $composedEmail, SMTP $smtp): void
    {
        /** @var \EmailSender\Core\ValueObject\EmailAddress $to */
        foreach ($composedEmail->getRecipients()->getTo() as $to) {
            $this->addRecipient($smtp, $to->getAddress()->getValue());
        }

        /** @var \EmailSender\Core\ValueObject\EmailAddress $cc */
        foreach ($composedEmail->getRecipients()->getCc() as $cc) {
            $this->addRecipient($smtp, $cc->getAddress()->getValue());
        }

        /** @var \EmailSender\Core\ValueObject\EmailAddress $bcc */
        foreach ($composedEmail->getRecipients()->getBcc() as $bcc) {
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
