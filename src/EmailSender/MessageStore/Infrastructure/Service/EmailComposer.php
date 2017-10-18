<?php

namespace EmailSender\MessageStore\Infrastructure\Service;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
use PHPMailer;

/**
 * Class EmailComposer
 *
 * @package EmailSender\MessageStore
 */
class EmailComposer implements EmailComposerInterface
{
    /**
     * @var \PHPMailer
     */
    private $phpMailer;

    /**
     * EmailBuilderWithPHPMailer constructor.
     *
     * @param \PHPMailer $phpMailer
     */
    public function __construct(PHPMailer $phpMailer)
    {
        $this->phpMailer = $phpMailer;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral
     * @throws \phpmailerException
     */
    public function composeEmailFromMessage(Message $message): StringLiteral
    {
        $this->phpMailer->XMailer = 'EmailSenderForSenorita';

        // Set the delay in the message date.
        $this->phpMailer->MessageDate = date('D, j M Y H:i:s O', time() + $message->getDelay()->getValue());

        $this->phpMailer->setFrom(
            $message->getFrom()->getAddress()->getValue(),
            $this->getDisplayNameAsString($message->getFrom())
        );

        /** @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress $toAddress */
        foreach ($message->getTo() as $toAddress) {
            $this->phpMailer->addAddress(
                $toAddress->getAddress()->getValue(),
                $this->getDisplayNameAsString($toAddress)
            );
        }

        /** @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress $ccAddress */
        foreach ($message->getCc() as $ccAddress) {
            $this->phpMailer->addCC($ccAddress->getAddress()->getValue(), $this->getDisplayNameAsString($ccAddress));
        }

        /** @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress $bccAddress */
        foreach ($message->getBcc() as $bccAddress) {
            $this->phpMailer->addBCC($bccAddress->getAddress()->getValue(), $this->getDisplayNameAsString($bccAddress));
        }

        if ($message->getReplyTo()) {
            $this->phpMailer->addReplyTo(
                $message->getReplyTo()->getAddress()->getValue(),
                $this->getDisplayNameAsString($message->getReplyTo())
            );
        }

        $this->phpMailer->Subject = $message->getSubject()->getValue();
        $this->phpMailer->Body    = $message->getBody()->getValue();

        $this->phpMailer->isHTML(false);

        $this->phpMailer->preSend();

        return new StringLiteral($this->phpMailer->getSentMIMEMessage());
    }

    /**
     * @param \EmailSender\MailAddress\Domain\Aggregate\MailAddress $mailAddress
     *
     * @return string
     */
    private function getDisplayNameAsString(MailAddress $mailAddress): string
    {
        $displayNameAsString = '';

        if ($mailAddress->getDisplayName()) {
            $displayNameAsString = $mailAddress->getDisplayName()->getValue();
        }

        return $displayNameAsString;
    }
}
