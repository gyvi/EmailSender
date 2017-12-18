<?php

namespace EmailSender\Email\Application\Service;

use EmailSender\ComposedEmail\Application\Service\ComposedEmailService;
use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Email\Application\Contract\EmailServiceInterface;
use EmailSender\Email\Domain\Factory\EmailFactory;
use EmailSender\Email\Domain\Service\AddEmailService;
use Closure;
use EmailSender\EmailLog\Application\Service\EmailLogService;
use EmailSender\EmailQueue\Application\Service\EmailQueueService;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use InvalidArgumentException;

/**
 * Class EmailService
 *
 * @package EmailSender\Email
 */
class EmailService implements EmailServiceInterface
{
    /**
     * @var \Closure
     */
    private $view;

    /**
     * @var \EmailSender\Email\Application\Service\LoggerInterface
     */
    private $logger;

    /**
     * @var \Closure
     */
    private $queueService;

    /**
     * @var array
     */
    private $queueServiceSettings;

    /**
     * @var \Closure
     */
    private $composedEmailReaderService;

    /**
     * @var \Closure
     */
    private $composedEmailWriterService;

    /**
     * @var \Closure
     */
    private $emailLogReaderService;

    /**
     * @var \Closure
     */
    private $emailLogWriterService;

    /**
     * @var \Closure
     */
    private $smtpService;

    /**
     * EmailService constructor.
     *
     * @param \Closure                 $view
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Closure                 $queueService
     * @param array                    $queueServiceSettings
     * @param \Closure                 $composedEmailReaderService
     * @param \Closure                 $composedEmailWriterService
     * @param \Closure                 $emailLogReaderService
     * @param \Closure                 $emailLogWriterService
     * @param \Closure                 $smtpService
     */
    public function __construct(
        Closure $view,
        LoggerInterface $logger,
        Closure $queueService,
        array $queueServiceSettings,
        Closure $composedEmailReaderService,
        Closure $composedEmailWriterService,
        Closure $emailLogReaderService,
        Closure $emailLogWriterService,
        Closure $smtpService
    ) {
        $this->view                       = $view;
        $this->logger                     = $logger;
        $this->queueService               = $queueService;
        $this->queueServiceSettings       = $queueServiceSettings;
        $this->composedEmailReaderService = $composedEmailReaderService;
        $this->composedEmailWriterService = $composedEmailWriterService;
        $this->emailLogReaderService      = $emailLogReaderService;
        $this->emailLogWriterService      = $emailLogWriterService;
        $this->smtpService                = $smtpService;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     */
    public function add(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        try {
            $postRequest = $request->getParsedBody();

            $composedEmailService = new ComposedEmailService(
                $this->logger,
                $this->composedEmailReaderService,
                $this->composedEmailWriterService,
                $this->smtpService
            );

            $emailLogService = new EmailLogService(
                $this->view,
                $this->logger,
                $this->emailLogReaderService,
                $this->emailLogWriterService
            );

            $emailQueueService = new EmailQueueService(
                $this->logger,
                $this->queueService,
                $this->queueServiceSettings
            );

            $emailAddressFactory           = new EmailAddressFactory();
            $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
            $emailFactory                  = new EmailFactory($emailAddressFactory, $emailAddressCollectionFactory);

            $addEmailService = new AddEmailService(
                $composedEmailService,
                $emailLogService,
                $emailQueueService,
                $emailFactory
            );

            $email = $addEmailService->add($postRequest);

            switch ($email->getEmailStatus()->getValue()) {
                case EmailStatusList::SENT:
                    $response = $response->withStatus(204);

                    break;
                case EmailStatusList::QUEUED:
                    $response = $response->withAddedHeader('Location', '/api/v1/emails/logs')
                                         ->withStatus(201);

                    break;
            }
        } catch (InvalidArgumentException $e) {
            $this->logger->warning($e->getMessage(), $e->getTrace());

            $response = $this->getErrorResponse($response, 400, $e->getMessage(), $e->getPrevious());
        } catch (Throwable $e) {
            $response = $this->getErrorResponse($response, 500, 'Something went wrong when adding a new email.', $e);
        }

        return $response;
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
