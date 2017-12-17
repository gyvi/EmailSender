<?php

namespace EmailSender\ComposedEmail\Application\Service;

use EmailSender\ComposedEmail\Application\Exception\ComposedEmailException;
use EmailSender\ComposedEmail\Domain\Service\SendComposedEmailService;
use EmailSender\ComposedEmail\Infrastructure\Service\SMTPSender;
use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\Core\Catalog\EmailStatusList;
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
use Throwable;

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
     * @throws \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     */
    public function add(Email $email): ComposedEmail
    {
        try {
            $addComposedEmailService = new AddComposedEmailService(
                $this->repositoryWriter,
                $this->getComposedEmailFactory()
            );

            $composedEmail = $addComposedEmailService->add($email);
        } catch (Throwable $e) {
            $this->logger->alert($e->getMessage(), $e->getTrace());

            throw new ComposedEmailException('Something went wrong when try to store the composed email.', 0, $e);
        }

        return $composedEmail;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     */
    public function get(UnsignedInteger $composedEmailId): ComposedEmail
    {
        try {
            $getComposedEmailService = new GetComposedEmailService($this->repositoryReader);

            $composedEmail = $getComposedEmailService->get($composedEmailId);
        } catch (Throwable $e) {
            $this->logger->alert($e->getMessage(), $e->getTrace());

            throw new ComposedEmailException('Something went wrong when try to read the composed email.', 0, $e);
        }

        return $composedEmail;
    }

    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @return \EmailSender\Core\ValueObject\EmailStatus
     *
     * @throws \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     */
    public function send(ComposedEmail $composedEmail): EmailStatus
    {
        try {
            $smtpSender               = new SMTPSender($this->smtpSenderService);
            $sendComposedEmailService = new SendComposedEmailService($smtpSender, $this->repositoryReader);

            $sendComposedEmailService->send($composedEmail);
        } catch (Throwable $e) {
            $this->logger->alert($e->getMessage(), $e->getTrace());

            throw new ComposedEmailException('Something went wrong when try to send the composed email.', 0, $e);
        }

        return new EmailStatus(EmailStatusList::STATUS_SENT);
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     *
     * @return \EmailSender\Core\ValueObject\EmailStatus
     *
     * @throws \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     */
    public function sendById(UnsignedInteger $composedEmailId): EmailStatus
    {
        try {
            $smtpSender               = new SMTPSender($this->smtpSenderService);
            $sendComposedEmailService = new SendComposedEmailService($smtpSender, $this->repositoryReader);

            $sendComposedEmailService->sendById($composedEmailId);
        } catch (Throwable $e) {
            $this->logger->alert($e->getMessage(), $e->getTrace());

            throw new ComposedEmailException('Something went wrong when try to send the composed email.', 0, $e);
        }

        return new EmailStatus(EmailStatusList::STATUS_SENT);
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
