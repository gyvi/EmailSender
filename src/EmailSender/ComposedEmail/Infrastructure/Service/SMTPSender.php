<?php

namespace EmailSender\ComposedEmail\Infrastructure\Service;

use EmailSender\ComposedEmail\Domain\Contract\SMTPSenderInterface;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use Closure;
use SMTP;

/**
 * Class SMTPSender
 *
 * @package EmailSender\ComposedEmail
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
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \EmailSender\ComposedEmail\Infrastructure\Service\SMTPException
     */
    public function send(ComposedEmail $composedEmail): ComposedEmail
    {
        /** @var SMTP $smtp */
        $smtp = ($this->smtpService)();

        if (!$smtp->mail($composedEmail->getFrom()->getAddress()->getValue())) {
            throw new SMTPException('Unable to set SMTP From: ' . $composedEmail->getFrom()->getAddress()->getValue());
        }

        $this->setRecipients($composedEmail, $smtp);

        if (!$smtp->data($composedEmail->getEmail()->getValue())) {
            throw new SMTPException('Unable to set SMTP body.');
        }

        $smtp->quit();
        $smtp->close();

        return $composedEmail;
    }

    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     * @param \SMTP                                                     $smtp
     *
     * @throws \EmailSender\ComposedEmail\Infrastructure\Service\SMTPException
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
     * @throws \EmailSender\ComposedEmail\Infrastructure\Service\SMTPException
     */
    private function addRecipient(SMTP $smtp, string $recipient)
    {
        if (!$smtp->recipient($recipient)) {
            throw new SMTPException('Unable to set SMTP recipient: ' . $recipient);
        }
    }
}
