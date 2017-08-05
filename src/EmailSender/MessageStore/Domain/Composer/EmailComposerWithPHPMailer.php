<?php

namespace EmailSender\MessageStore\Domain\Composer;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
use PHPMailer;

/**
 * Class EmailComposerWithPHPMailer
 *
 * @package EmailSender\MessageStore
 */
class EmailComposerWithPHPMailer implements EmailComposerInterface
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
     */
    public function composeEmailFromMessage(Message $message): StringLiteral
    {
        $this->phpMailer->XMailer     = 'EmailSenderForSenoritaaaa';

        // Set the delay in the message date.
        $this->phpMailer->MessageDate = date('D, j M Y H:i:s O', time() + $message->getDelay()->getValue());

        $this->phpMailer->setFrom(
            $message->getFrom()->getAddress()->getValue(),
            (!empty($message->getFrom()->getDisplayName()) ? $message->getFrom()->getDisplayName()->getValue() : '')
        );

        /** @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress $toAddress */
        foreach ($message->getTo() as $toAddress) {
            $this->phpMailer->addAddress(
                $toAddress->getAddress()->getValue(),
                (!empty($toAddress->getDisplayName()) ? $toAddress->getDisplayName()->getValue() : '')
            );
        }

        /** @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress $ccAddress */
        foreach ($message->getCc() as $ccAddress) {
            $this->phpMailer->addCC(
                $ccAddress->getAddress()->getValue(),
                (!empty($ccAddress->getDisplayName()) ? $ccAddress->getDisplayName()->getValue() : '')
            );
        }

        /** @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress $bccAddress */
        foreach ($message->getBcc() as $bccAddress) {
            $this->phpMailer->addBCC(
                $bccAddress->getAddress()->getValue(),
                (!empty($bccAddress->getDisplayName()) ? $bccAddress->getDisplayName()->getValue() : '')
            );
        }

        if ($message->getReplyTo()) {
            $this->phpMailer->addReplyTo(
                $message->getReplyTo()->getAddress()->getValue(),
                (!empty($message->getReplyTo()->getDisplayName())
                    ? $message->getReplyTo()->getDisplayName()->getValue() : '')
            );
        }

        $this->phpMailer->Subject = $message->getSubject()->getValue();
        $this->phpMailer->Body    = $message->getBody()->getValue();

        $this->phpMailer->isHTML(false);

        $this->phpMailer->preSend();

        return new StringLiteral($this->phpMailer->getSentMIMEMessage());
    }
}