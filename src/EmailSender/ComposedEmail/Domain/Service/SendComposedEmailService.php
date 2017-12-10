<?php

namespace EmailSender\ComposedEmail\Domain\Service;

use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface;
use EmailSender\ComposedEmail\Domain\Contract\SMTPSenderInterface;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailStatus;

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
     *
     * @return \EmailSender\Core\ValueObject\EmailStatus
     */
    public function send(ComposedEmail $composedEmail): EmailStatus
    {
        return $this->smtpSenderInterface->send($composedEmail);
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     *
     * @return \EmailSender\Core\ValueObject\EmailStatus
     */
    public function sendById(UnsignedInteger $composedEmailId): EmailStatus
    {
        $composedEmail = $this->repositoryReader->get($composedEmailId);

        return $this->smtpSenderInterface->send($composedEmail);
    }
}
