<?php

namespace EmailSender\ComposedEmail\Domain\Service;

use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface;
use EmailSender\ComposedEmail\Domain\Contract\SMTPSenderInterface;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

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
     * @var \EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface
     */
    private $repositoryReader;

    /**
     * SendComposedEmailByIdService constructor.
     *
     * @param \EmailSender\ComposedEmail\Domain\Contract\SMTPSenderInterface                    $smtpSenderInterface
     * @param \EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface $repositoryReader
     */
    public function __construct(
        SMTPSenderInterface $smtpSenderInterface,
        ComposedEmailRepositoryReaderInterface $repositoryReader
    ) {
        $this->smtpSenderInterface = $smtpSenderInterface;
        $this->repositoryReader    = $repositoryReader;
    }

    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     */
    public function send(ComposedEmail $composedEmail): void
    {
        $this->smtpSenderInterface->send($composedEmail);
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     */
    public function sendById(UnsignedInteger $composedEmailId): void
    {
        $composedEmail = $this->repositoryReader->get($composedEmailId);

        $this->smtpSenderInterface->send($composedEmail);
    }
}
