<?php

namespace EmailSender\EmailQueue\Application\Service;

use EmailSender\Email\Application\Service\EmailService;
use EmailSender\EmailLog\Application\Service\EmailLogService;
use EmailSender\EmailQueue\Application\Contract\EmailQueueServiceInterface;
use EmailSender\EmailQueue\Application\Validator\EmailQueueAddRequestValidator;
use EmailSender\EmailQueue\Domain\Service\AddEmailQueueService;
use EmailSender\EmailQueue\Domain\Service\SendEmailService;
use EmailSender\EmailQueue\Infrastructure\Factory\AMQPMessageFactory;
use EmailSender\EmailQueue\Infrastructure\Service\EmailQueueRepositoryWriter;
use EmailSender\EmailQueue\Infrastructure\Service\SMTPSender;
use EmailSender\ComposedEmail\Application\Service\ComposedEmailService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;
use Closure;
use Psr\Log\LoggerInterface;
use EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory;

/**
 * Class EmailQueueService
 *
 * @package EmailSender\EmailQueue\Application\Service
 */
class EmailQueueService implements EmailQueueServiceInterface
{
    /**
     * @var \Closure
     */
    private $view;

    /**
     * @var \Psr\Log\LoggerInterface
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
     * EmailQueueService constructor.
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
     * @throws \Error
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \phpmailerException
     */
    public function add(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        $postRequest = $request->getParsedBody();

        (new EmailQueueAddRequestValidator())->validate($postRequest);

        $emailService = new EmailService();

        $composedEmailService = new ComposedEmailService(
            $this->logger,
            $this->composedEmailReaderService,
            $this->composedEmailWriterService
        );

        $emailLogService = new EmailLogService(
            $this->view,
            $this->logger,
            $this->emailLogReaderService,
            $this->emailLogWriterService
        );

        $amqpMessageFactory = new AMQPMessageFactory();

        $queueWriter = new EmailQueueRepositoryWriter(
            $this->queueService,
            $amqpMessageFactory,
            $this->queueServiceSettings['queue'],
            $this->queueServiceSettings['exchange']
        );

        $emailQueueFactory    = new EmailQueueFactory();
        $addEmailQueueService = new AddEmailQueueService(
            $queueWriter,
            $emailService,
            $composedEmailService,
            $emailLogService,
            $emailQueueFactory
        );

        $addEmailQueueService->add($postRequest);

        /** @var \Slim\Http\Response $response */
        $response = $response->withJson([
            'status' => 0,
            'statusMessage' => 'Queued.',
        ]);

        return $response;
    }

    /**
     * @param string $emailQueue
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \EmailSender\EmailQueue\Infrastructure\Service\SMTPException
     * @throws \Error
     * @throws \InvalidArgumentException
     */
    public function sendEmailFromQueue(string $emailQueue): void
    {
        $composedEmailService = new ComposedEmailService(
            $this->logger,
            $this->composedEmailReaderService,
            $this->composedEmailWriterService
        );

        $emailLogService = new EmailLogService(
            $this->view,
            $this->logger,
            $this->emailLogReaderService,
            $this->emailLogWriterService
        );

        $smtpSender = new SMTPSender($this->smtpService);

        $sendEmailService = new SendEmailService($emailLogService, $composedEmailService, $smtpSender);

        $sendEmailService->send($emailQueue);
    }
}
