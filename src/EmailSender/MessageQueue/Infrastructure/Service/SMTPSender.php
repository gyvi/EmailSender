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
    private $SMTPService;

    /**
     * SMTPSender constructor.
     *
     * @param \Closure $SMTPService
     */
    public function __construct(Closure $SMTPService)
    {
        $this->SMTPService = $SMTPService;
    }

    /**
     * @param \EmailSender\MessageLog\Domain\Aggregate\MessageLog     $messageLog
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @throws \EmailSender\MessageQueue\Infrastructure\Service\SMTPException
     */
    public function send(MessageLog $messageLog, MessageStore $messageStore): void
    {
        /** @var SMTP $SMTP */
        $SMTP = ($this->SMTPService)();

        if (!$SMTP->mail($messageLog->getFrom()->getAddress()->getValue())) {
            throw new SMTPException('Unable to set SMTP From: ' . $messageLog->getFrom()->getAddress()->getValue());
        }

        $this->setRecipients($messageStore, $SMTP);

        if (!$SMTP->data($messageStore->getMessage()->getValue())) {
            throw new SMTPException('Unable to set SMTP body.');
        }

        $SMTP->quit();
        $SMTP->close();
    }

    /**
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     * @param \SMTP                                                   $SMTP
     */
    private function setRecipients(MessageStore $messageStore, SMTP $SMTP): void
    {
        /** @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress $to */
        foreach ($messageStore->getRecipients()->getTo() as $to) {
            $this->addRecipient($SMTP, $to->getAddress()->getValue());
        }

        /** @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress $cc */
        foreach ($messageStore->getRecipients()->getCc() as $cc) {
            $this->addRecipient($SMTP, $cc->getAddress()->getValue());
        }

        /** @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress $bcc */
        foreach ($messageStore->getRecipients()->getBcc() as $bcc) {
            $this->addRecipient($SMTP, $bcc->getAddress()->getValue());
        }
    }

    /**
     * @param \SMTP  $SMTP
     * @param string $recipient
     *
     * @throws \Exception
     */
    private function addRecipient(SMTP $SMTP, string $recipient)
    {
        if (!$SMTP->recipient($recipient)) {
            throw new SMTPException('Unable to set SMTP recipient: ' . $recipient);
        }
    }
}
