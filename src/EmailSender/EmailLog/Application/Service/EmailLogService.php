<?php

namespace EmailSender\EmailLog\Application\Service;

use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Core\Scalar\Application\Factory\DateTimeFactory;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface;
use EmailSender\EmailLog\Application\Exception\EmailLogException;
use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailLog\Domain\Factory\ListRequestFactory;
use EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory;
use EmailSender\EmailLog\Domain\Service\AddEmailLogService;
use EmailSender\EmailLog\Domain\Service\GetEmailLogService;
use EmailSender\EmailLog\Domain\Service\ListEmailLogService;
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
use Throwable;
use InvalidArgumentException;

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
        $this->view                = $view;
        $this->logger              = $logger;

        $emailLogFactory           = $this->getEmailLogFactory();
        $emailLogCollectionFactory = new EmailLogCollectionFactory($emailLogFactory);

        $this->repositoryReader    = new EmailLogRepositoryReader(
            $emailLogReaderService,
            $emailLogFactory,
            $emailLogCollectionFactory
        );

        $this->repositoryWriter    = new EmailLogRepositoryWriter($emailLogWriterService);
    }

    /**
     * @param \EmailSender\Email\Domain\Aggregate\Email                 $email
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     *
     * @throws \EmailSender\EmailLog\Application\Exception\EmailLogException
     */
    public function add(Email $email, ComposedEmail $composedEmail): EmailLog
    {
        try {
            $addEmailLogService = new AddEmailLogService($this->repositoryWriter, $this->getEmailLogFactory());
            $emailLog           = $addEmailLogService->add($email, $composedEmail);
        } catch (Throwable $e) {
            $this->logger->alert($e->getMessage(), $e->getTrace());

            throw new EmailLogException('Something went wrong when adding email log.', 0, $e);
        }

        return $emailLog;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $emailLogId
     * @param \EmailSender\Core\ValueObject\EmailStatus                                $emailLogStatus
     * @param null|string                                                              $errorMessageString
     *
     * @throws \EmailSender\EmailLog\Application\Exception\EmailLogException
     */
    public function setStatus(
        UnsignedInteger $emailLogId,
        EmailStatus $emailLogStatus,
        ?string $errorMessageString
    ): void {
        try {
            $errorMessage          = new StringLiteral((string)$errorMessageString);
            $updateEmailLogService = new UpdateEmailLogService($this->repositoryWriter);

            $updateEmailLogService->setStatus($emailLogId, $emailLogStatus, $errorMessage);
        } catch (Throwable $e) {
            $this->logger->alert($e->getMessage(), $e->getTrace());

            throw new EmailLogException('Something went wrong when setting email log status.', 0, $e);
        }
    }

    /**
     * @param int $emailLogIdInt
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     * @throws \EmailSender\EmailLog\Application\Exception\EmailLogException
     */
    public function get(int $emailLogIdInt): EmailLog
    {
        try {
            $emailLogId         = new UnsignedInteger($emailLogIdInt);
            $getEmailLogService = new GetEmailLogService($this->repositoryReader);

            $emailLog           = $getEmailLogService->get($emailLogId);
        } catch (Throwable $e) {
            $this->logger->alert($e->getMessage(), $e->getTrace());

            throw new EmailLogException('Something went wrong when reading email log.', 0, $e);
        }

        return $emailLog;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function list(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        try {
            $queryParams = $request->getQueryParams();

            $emailAddressFactory = new EmailAddressFactory();
            $listRequestFactory  = new ListRequestFactory($emailAddressFactory);
            $getEmailLogService  = new ListEmailLogService($this->repositoryReader, $listRequestFactory);

            $emailLogCollection  = $getEmailLogService->list($queryParams);

            /** @var \Slim\Http\Response $response */
            $response = $response->withJson(['data' => $emailLogCollection])
                                 ->withStatus(200);
        } catch (InvalidArgumentException $e) {
            $this->logger->warning($e->getMessage(), $e->getTrace());

            $response = $this->getErrorResponse($response, 400, $e->getMessage(), $e->getPrevious());
        } catch (Throwable $e) {
            $this->logger->alert($e->getMessage(), $e->getTrace());

            $response = $this->getErrorResponse($response, 500, 'Something went wrong when list email logs.', $e);
        }

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

    /**
     * @return \EmailSender\EmailLog\Domain\Factory\EmailLogFactory
     */
    private function getEmailLogFactory(): EmailLogFactory
    {
        $emailAddressFactory           = new EmailAddressFactory();
        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
        $recipientsFactory             = new RecipientsFactory($emailAddressCollectionFactory);
        $dateTimeFactory               = new DateTimeFactory();

        return new EmailLogFactory(
            $recipientsFactory,
            $emailAddressFactory,
            $dateTimeFactory
        );
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int                                 $status
     * @param string                              $message
     * @param null|\Throwable                     $error
     *
     * @return \Psr\Http\Message\ResponseInterface|\Slim\Http\Response
     */
    private function getErrorResponse(ResponseInterface $response, int $status, string $message, ?Throwable $error)
    {
        $responseArray = ['message' => $message];

        if ($error) {
            $responseArray['description'] = $error->getMessage();
        }

        /** @var \Slim\Http\Response $response */
        $response = $response->withJson($responseArray)->withStatus($status);

        return $response;
    }
}
