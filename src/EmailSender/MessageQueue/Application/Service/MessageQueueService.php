<?php

namespace EmailSender\MessageQueue\Application\Service;

use EmailSender\Message\Application\Service\MessageService;
use EmailSender\MessageLog\Application\Service\MessageLogService;
use EmailSender\MessageQueue\Application\Contract\MessageQueueServiceInterface;
use EmailSender\MessageQueue\Application\Validator\MessageQueueAddRequestValidator;
use EmailSender\MessageQueue\Domain\Service\AddMessageQueueService;
use EmailSender\MessageQueue\Domain\Service\SendMessageService;
use EmailSender\MessageQueue\Infrastructure\Builder\AMQPMessageBuilder;
use EmailSender\MessageQueue\Infrastructure\Service\MessageQueueRepositoryWriter;
use EmailSender\MessageQueue\Infrastructure\Service\SMTPSender;
use EmailSender\MessageStore\Application\Service\MessageStoreService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;
use Closure;
use Psr\Log\LoggerInterface;
use EmailSender\MessageQueue\Domain\Factory\MessageQueueFactory;

/**
 * Class MessageQueueService
 *
 * @package EmailSender\MessageQueue\Application\Service
 */
class MessageQueueService implements MessageQueueServiceInterface
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
    private $messageStoreReaderService;

    /**
     * @var \Closure
     */
    private $messageStoreWriterService;

    /**
     * @var \Closure
     */
    private $messageLogReaderService;

    /**
     * @var \Closure
     */
    private $messageLogWriterService;

    /**
     * @var \Closure
     */
    private $smtpService;

    /**
     * MessageQueueService constructor.
     *
     * @param \Closure                 $view
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Closure                 $queueService
     * @param array                    $queueServiceSettings
     * @param \Closure                 $messageStoreReaderService
     * @param \Closure                 $messageStoreWriterService
     * @param \Closure                 $messageLogReaderService
     * @param \Closure                 $messageLogWriterService
     * @param \Closure                 $smtpService
     */
    public function __construct(
        Closure $view,
        LoggerInterface $logger,
        Closure $queueService,
        array $queueServiceSettings,
        Closure $messageStoreReaderService,
        Closure $messageStoreWriterService,
        Closure $messageLogReaderService,
        Closure $messageLogWriterService,
        Closure $smtpService
    ) {
        $this->view                      = $view;
        $this->logger                    = $logger;
        $this->queueService              = $queueService;
        $this->queueServiceSettings      = $queueServiceSettings;
        $this->messageStoreReaderService = $messageStoreReaderService;
        $this->messageStoreWriterService = $messageStoreWriterService;
        $this->messageLogReaderService   = $messageLogReaderService;
        $this->messageLogWriterService   = $messageLogWriterService;
        $this->smtpService               = $smtpService;
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

        (new MessageQueueAddRequestValidator())->validate($postRequest);

        $messageService = new MessageService();

        $messageStoreService = new MessageStoreService(
            $this->logger,
            $this->messageStoreReaderService,
            $this->messageStoreWriterService
        );

        $messageLogService = new MessageLogService(
            $this->view,
            $this->logger,
            $this->messageLogReaderService,
            $this->messageLogWriterService
        );

        $amqpMessageBuilder = new AMQPMessageBuilder();

        $queueWriter = new MessageQueueRepositoryWriter(
            $this->queueService,
            $amqpMessageBuilder,
            $this->queueServiceSettings['queue'],
            $this->queueServiceSettings['exchange']
        );

        $messageQueueBuilder    = new MessageQueueFactory();
        $addMessageQueueService = new AddMessageQueueService(
            $queueWriter,
            $messageService,
            $messageStoreService,
            $messageLogService,
            $messageQueueBuilder
        );

        $addMessageQueueService->add($postRequest);

        /** @var \Slim\Http\Response $response */
        $response = $response->withJson([
            'status' => 0,
            'statusMessage' => 'Queued.',
        ]);

        return $response;
    }

    /**
     * @param string $messageQueue
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \EmailSender\MessageQueue\Infrastructure\Service\SMTPException
     * @throws \Error
     * @throws \InvalidArgumentException
     */
    public function sendMessageFromQueue(string $messageQueue): void
    {
        $messageStoreService = new MessageStoreService(
            $this->logger,
            $this->messageStoreReaderService,
            $this->messageStoreWriterService
        );

        $messageLogService = new MessageLogService(
            $this->view,
            $this->logger,
            $this->messageLogReaderService,
            $this->messageLogWriterService
        );

        $smtpSender = new SMTPSender($this->smtpService);

        $sendMessageService = new SendMessageService($messageLogService, $messageStoreService, $smtpSender);

        $sendMessageService->send($messageQueue);
    }
}
