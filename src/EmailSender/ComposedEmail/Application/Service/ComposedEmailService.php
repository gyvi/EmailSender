<?php

namespace EmailSender\ComposedEmail\Application\Service;

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
     * ComposedEmailService constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Closure                 $composedEmailReaderService
     * @param \Closure                 $composedEmailWriterService
     */
    public function __construct(
        LoggerInterface $logger,
        Closure $composedEmailReaderService,
        Closure $composedEmailWriterService
    ) {
        $this->logger           = $logger;
        $this->repositoryReader = new ComposedEmailRepositoryReader($composedEmailReaderService);
        $this->repositoryWriter = new ComposedEmailRepositoryWriter($composedEmailWriterService);
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
        $phpMailer                     = new PHPMailer();
        $emailComposer                 = new EmailComposer($phpMailer);
        $emailAddressFactory           = new EmailAddressFactory();
        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
        $recipientsFactory             = new RecipientsFactory($emailAddressCollectionFactory);
        $composedEmailFactory          = new ComposedEmailFactory($emailComposer, $recipientsFactory);
        $addComposedEmailService       = new AddComposedEmailService($this->repositoryWriter, $composedEmailFactory);

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
        $phpMailer                     = new PHPMailer();
        $emailComposer                 = new EmailComposer($phpMailer);
        $emailAddressFactory           = new EmailAddressFactory();
        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
        $recipientsFactory             = new RecipientsFactory($emailAddressCollectionFactory);
        $composedEmailFactory          = new ComposedEmailFactory($emailComposer, $recipientsFactory);
        $getComposedEmailService       = new GetComposedEmailService($this->repositoryReader, $composedEmailFactory);

        return $getComposedEmailService->get($composedEmailId);
    }
}
