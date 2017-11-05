<?php

namespace EmailSender\EmailLog\Application\Service;

use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Core\Scalar\Application\Factory\DateTimeFactory;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface;
use EmailSender\EmailLog\Application\Validator\ListRequestValidator;
use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailLog\Domain\Factory\ListRequestFactory;
use EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory;
use EmailSender\EmailLog\Domain\Service\AddEmailLogService;
use EmailSender\EmailLog\Domain\Service\GetEmailLogService;
use EmailSender\EmailLog\Domain\Service\UpdateEmailLogService;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use Closure;
use Psr\Log\LoggerInterface;
use EmailSender\EmailLog\Infrastructure\Persistence\EmailLogRepositoryReader;
use EmailSender\EmailLog\Infrastructure\Persistence\EmailLogRepositoryWriter;
use EmailSender\EmailLog\Domain\Factory\EmailLogFactory;

/**
 * Class EmailLogService
 *
 * @package EmailSender\EmailLog
 */
class EmailLogService implements EmailLogServiceInterface
{
    /**
     * @var \Slim\Views\Twig
     */
    private $view;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface
     */
    private $repositoryReader;

    /**
     * @var \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryWriterInterface
     */
    private $repositoryWriter;

    /**
     * EmailLogService constructor.
     *
     * @param \Closure                 $view
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Closure                 $emailLogReaderService
     * @param \Closure                 $emailLogWriterService
     */
    public function __construct(
        Closure $view,
        LoggerInterface $logger,
        Closure $emailLogReaderService,
        Closure $emailLogWriterService
    ) {
        $this->view             = $view;
        $this->logger           = $logger;
        $this->repositoryReader = new EmailLogRepositoryReader($emailLogReaderService);
        $this->repositoryWriter = new EmailLogRepositoryWriter($emailLogWriterService);
    }

    /**
     * @param \EmailSender\Email\Domain\Aggregate\Email               $email
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     */
    public function add(Email $email, ComposedEmail $composedEmail): EmailLog
    {
        $emailAddressFactory           = new EmailAddressFactory();
        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
        $recipientsFactory             = new RecipientsFactory($emailAddressCollectionFactory);

        $dateTimeFactory               = new DateTimeFactory();
        $emailLogFactory               = new EmailLogFactory(
            $recipientsFactory,
            $emailAddressFactory,
            $dateTimeFactory
        );

        $addEmailLogService            = new AddEmailLogService($this->repositoryWriter, $emailLogFactory);

        return $addEmailLogService->add($email, $composedEmail);
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $emailLogId
     * @param \EmailSender\Core\ValueObject\EmailStatus                                $emailLogStatus
     * @param null|string                                                              $errorMessageString
     */
    public function setStatus(
        UnsignedInteger $emailLogId,
        EmailStatus $emailLogStatus,
        ?string $errorMessageString
    ): void {
        $errorMessage          = new StringLiteral((string)$errorMessageString);
        $updateEmailLogService = new UpdateEmailLogService($this->repositoryWriter);

        $updateEmailLogService->setStatus($emailLogId, $emailLogStatus, $errorMessage);
    }

    /**
     * @param int $emailLogIdInt
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function get(int $emailLogIdInt): EmailLog
    {
        $emailLogId                    = new UnsignedInteger($emailLogIdInt);
        $emailAddressFactory           = new EmailAddressFactory();
        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
        $recipientsFactory             = new RecipientsFactory($emailAddressCollectionFactory);

        $dateTimeFactory               = new DateTimeFactory();
        $emailLogFactory               = new EmailLogFactory(
            $recipientsFactory,
            $emailAddressFactory,
            $dateTimeFactory
        );

        $emailLogCollectionFactory     = new EmailLogCollectionFactory($emailLogFactory);
        $listRequestFactory            = new ListRequestFactory($emailAddressFactory);

        $getEmailLogService            = new GetEmailLogService(
            $this->repositoryReader,
            $emailLogFactory,
            $emailLogCollectionFactory,
            $listRequestFactory
        );

        return $getEmailLogService->get($emailLogId);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function list(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        $queryParams = $request->getQueryParams();

        (new ListRequestValidator())->validate($queryParams);

        $emailAddressFactory           = new EmailAddressFactory();
        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
        $recipientsFactory             = new RecipientsFactory($emailAddressCollectionFactory);

        $dateTimeFactory               = new DateTimeFactory();
        $emailLogFactory               = new EmailLogFactory(
            $recipientsFactory,
            $emailAddressFactory,
            $dateTimeFactory
        );

        $emailLogCollectionFactory     = new EmailLogCollectionFactory($emailLogFactory);
        $listRequestFactory            = new ListRequestFactory($emailAddressFactory);

        $getEmailLogService            = new GetEmailLogService(
            $this->repositoryReader,
            $emailLogFactory,
            $emailLogCollectionFactory,
            $listRequestFactory
        );

        $emailLogCollection = $getEmailLogService->list($queryParams);

        /** @var \Slim\Http\Response $response */
        $response = $response->withJson(['data' => $emailLogCollection])
                             ->withStatus(200);

        return $response;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     */
    public function lister(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        /** @var \Slim\Views\Twig $twig */
        $twig = ($this->view)();

        return $twig->render($response, 'EmailLog/Application/View/emailLogLister.twig');
    }
}
