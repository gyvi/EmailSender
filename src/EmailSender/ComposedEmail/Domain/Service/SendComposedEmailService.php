<?php

namespace EmailSender\ComposedEmail\Domain\Service;

use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\ComposedEmail\Domain\Contract\SMTPSenderInterface;

/**
 * Class SendComposedEmailService
 *
 * @package EmailSender\ComposedEmail
 */
class SendComposedEmailService
{
    /**
     * @var \EmailSender\ComposedEmail\Domain\Contract\SMTPSenderInterface
     */
    private $smtpSenderInterface;

    /**
     * SendComposedEmailService constructor.
     *
     * @param \EmailSender\ComposedEmail\Domain\Contract\SMTPSenderInterface $smtpSenderInterface
     */
    public function __construct(SMTPSenderInterface $smtpSenderInterface)
    {
        $this->smtpSenderInterface = $smtpSenderInterface;
    }

    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     */
    public function send(ComposedEmail $composedEmail): void
    {
        $this->smtpSenderInterface->send($composedEmail);
    }
}
