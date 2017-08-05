<?php

namespace EmailSender\MessageStore\Domain\Builder;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\MailAddress\Application\Collection\MailAddressCollection;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Contract\MessageStoreBuilderInterface;
use PHPMailer;

class MessageStoreBuilderWithPHPMailer implements MessageStoreBuilderInterface
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
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function buildMessageStoreFromMessage(Message $message): MessageStore
    {
        $this->phpMailer->XMailer     = 'EmailSenderForSenoritaaaa';

        // Set the delay in the message date.
        $this->phpMailer->MessageDate = date('D, j M Y H:i:s O', time() + $message->getDelay()->getValue());

        $this->phpMailer->setFrom(
            $message->getFrom()->getAddress()->getValue(),
            (!empty($message->getFrom()->getDisplayName()) ? $message->getFrom()->getDisplayName()->getValue() : '')
        );

        $this->addAllRecipients($message);

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



        return new MessageStore(
            new MailAddressCollection(),
            new StringLiteral($this->phpMailer->getSentMIMEMessage()),
        );
    }

    /**
     * Add all recipients to PHPMailer.
     *
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     */
    private function addAllRecipients(Message $message): void
    {
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
    }
}