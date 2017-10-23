<?php

namespace EmailSender\ComposedEmail\Infrastructure\Service;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\ComposedEmail\Domain\Contract\EmailComposerInterface;
use PHPMailer;

/**
 * Class EmailComposer
 *
 * @package EmailSender\ComposedEmail
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
     * @param \EmailSender\Email\Domain\Aggregate\Email $email
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral
     *
     * @throws \phpmailerException
     */
    public function compose(Email $email): StringLiteral
    {
        $this->phpMailer->XMailer = 'EmailSenderForSenorita';

        // Set the delay in the message date.
        $this->phpMailer->MessageDate = date('D, j M Y H:i:s O', time() + $email->getDelay()->getValue());

        $this->phpMailer->setFrom(
            $email->getFrom()->getAddress()->getValue(),
            (string)$email->getFrom()->getDisplayName()
        );

        /** @var \EmailSender\Core\ValueObject\EmailAddress $toAddress */
        foreach ($email->getTo() as $toAddress) {
            $this->phpMailer->addAddress($toAddress->getAddress()->getValue(), (string)$toAddress->getDisplayName());
        }

        /** @var \EmailSender\Core\ValueObject\EmailAddress $ccAddress */
        foreach ($email->getCc() as $ccAddress) {
            $this->phpMailer->addCC($ccAddress->getAddress()->getValue(), (string)$ccAddress->getDisplayName());
        }

        /** @var \EmailSender\Core\ValueObject\EmailAddress $bccAddress */
        foreach ($email->getBcc() as $bccAddress) {
            $this->phpMailer->addBCC($bccAddress->getAddress()->getValue(), (string)$bccAddress->getDisplayName());
        }

        if ($email->getReplyTo()) {
            $this->phpMailer->addReplyTo(
                $email->getReplyTo()->getAddress()->getValue(),
                (string)$email->getReplyTo()->getDisplayName()
            );
        }

        $this->phpMailer->Subject = $email->getSubject()->getValue();
        $this->phpMailer->Body    = $email->getBody()->getValue();

        $this->phpMailer->isHTML(false);

        $this->phpMailer->preSend();

        return new StringLiteral($this->phpMailer->getSentMIMEMessage());
    }
}
