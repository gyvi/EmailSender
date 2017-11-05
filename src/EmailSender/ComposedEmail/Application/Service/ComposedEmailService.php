<?php

namespace EmailSender\ComposedEmail\Application\Service;

use EmailSender\ComposedEmail\Domain\Service\SendComposedEmailByIdService;
use EmailSender\ComposedEmail\Domain\Service\SendComposedEmailService;
use EmailSender\ComposedEmail\Infrastructure\Service\SMTPSender;
use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\ComposedEmail\Application\Contract\ComposedEmailServiceInterface;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\ComposedEmail\Domain\Service\AddComposedEmailService;
use EmailSender\ComposedEmail\Domain\Service\GetComposedEmailService;
use EmailSender\ComposedEmail\Infrastructure\Persistence\ComposedEmailRepositoryReader;
use EmailSender\ComposedEmail\Infrastructure\Persistence\ComposedEmailRepositoryWriter;
use EmailSender\ComposedEmail\Infrastructure\Service\EmailComposer;
use Closure;
use Psr\Log\LoggerInterface;
use EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory;
use PHPMailer;

/**
 * Class ComposedEmailService
 *
 * @package EmailSender\ComposedEmail
 */
class ComposedEmailService implements ComposedEmailServiceInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \EmailSender\ComposedEmail\Infrastructure\Persistence\ComposedEmailRepositoryReader
     */
    private $repositoryReader;

    /**
     * @var \EmailSender\ComposedEmail\Infrastructure\Persistence\ComposedEmailRepositoryWriter
     */
    private $repositoryWriter;

    /**
     * @var \Closure
     */
    private $smtpSenderService;

    /**
     * ComposedEmailService constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Closure                 $composedEmailReaderService
     * @param \Closure                 $composedEmailWriterService
     * @param \Closure                 $smtpSenderService
     */
    public function __construct(
        LoggerInterface $logger,
        Closure $composedEmailReaderService,
        Closure $composedEmailWriterService,
        Closure $smtpSenderService
    ) {
        $this->logger           = $logger;
        $this->repositoryWriter = new ComposedEmailRepositoryWriter($composedEmailWriterService);
        $this->repositoryReader = new ComposedEmailRepositoryReader(
            $composedEmailReaderService,
            $this->getComposedEmailFactory()
        );
        $this->smtpSenderService = $smtpSenderService;
    }

    /**
     * @param \EmailSender\Email\Domain\Aggregate\Email $email
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \Error
     * @throws \InvalidArgumentException
     * @throws \phpmailerException
     */
    public function add(Email $email): ComposedEmail
    {
        $addComposedEmailService = new AddComposedEmailService(
            $this->repositoryWriter,
            $this->getComposedEmailFactory()
        );

        return $addComposedEmailService->add($email);
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \Error
     * @throws \InvalidArgumentException
     */
    public function get(UnsignedInteger $composedEmailId): ComposedEmail
    {
        $getComposedEmailService = new GetComposedEmailService($this->repositoryReader);

        return $getComposedEmailService->get($composedEmailId);
    }

    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     */
    public function send(ComposedEmail $composedEmail): void
    {
        $smtpSender               = new SMTPSender($this->smtpSenderService);
        $sendComposedEmailService = new SendComposedEmailService($smtpSender);

        $sendComposedEmailService->send($composedEmail);
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     */
    public function sendById(UnsignedInteger $composedEmailId): void
    {
        $smtpSender                   = new SMTPSender($this->smtpSenderService);
        $sendComposedEmailByIdService = new SendComposedEmailByIdService($smtpSender, $this->repositoryReader);

        $sendComposedEmailByIdService->send($composedEmailId);
    }

    /**
     * @return \EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory
     */
    private function getComposedEmailFactory(): ComposedEmailFactory
    {
        $phpMailer                     = new PHPMailer();
        $emailComposer                 = new EmailComposer($phpMailer);
        $emailAddressFactory           = new EmailAddressFactory();
        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
        $recipientsFactory             = new RecipientsFactory($emailAddressCollectionFactory);

        return new ComposedEmailFactory($emailComposer, $recipientsFactory, $emailAddressFactory);
    }
}
