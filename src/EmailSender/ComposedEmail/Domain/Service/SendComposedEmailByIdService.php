<?php

namespace EmailSender\ComposedEmail\Domain\Service;

use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface;
use EmailSender\ComposedEmail\Domain\Contract\SMTPSenderInterface;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

/**
 * Class SendComposedEmailByIdService
 *
 * @package EmailSender\ComposedEmail
 */
class SendComposedEmailByIdService
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
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     *
     * @throws \EmailSender\ComposedEmail\Infrastructure\Service\SMTPException
     * @throws \Exception
     */
    public function send(UnsignedInteger $composedEmailId): void
    {
        $composedEmail = $this->repositoryReader->get($composedEmailId);

        $this->smtpSenderInterface->send($composedEmail);
    }
}
