<?php

namespace EmailSender\MessageQueue\Application\Service;

use EmailSender\Message\Application\Service\MessageService;
use EmailSender\MessageLog\Application\Service\MessageLogService;
use EmailSender\MessageQueue\Application\Contract\MessageQueueServiceInterface;
use EmailSender\MessageQueue\Application\Validator\MessageQueueAddRequestValidator;
use EmailSender\MessageQueue\Domain\Builder\MessageQueueBuilder;
use EmailSender\MessageStore\Application\Service\MessageStoreService;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;
use Closure;
use Psr\Log\LoggerInterface;

/**
 * Class MessageQueueService
 *
 * @package EmailSender\MessageQueue\Application\Service
 */
class MessageQueueService implements MessageQueueServiceInterface
{
    /**
     * @var \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface
     */
    private $emailComposer;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

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
     * MessageQueueService constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface $emailComposer
     * @param \Psr\Log\LoggerInterface                                         $logger
     * @param \Closure                                                         $messageStoreReaderService
     * @param \Closure                                                         $messageStoreWriterService
     * @param \Closure                                                         $messageLogReaderService
     * @param \Closure                                                         $messageLogWriterService
     */
    public function __construct(
        EmailComposerInterface $emailComposer,
        LoggerInterface $logger,
        Closure $messageStoreReaderService,
        Closure $messageStoreWriterService,
        Closure $messageLogReaderService,
        Closure $messageLogWriterService
    ) {
        $this->emailComposer             = $emailComposer;
        $this->logger                    = $logger;
        $this->messageStoreReaderService = $messageStoreReaderService;
        $this->messageStoreWriterService = $messageStoreWriterService;
        $this->messageLogReaderService   = $messageLogReaderService;
        $this->messageLogWriterService   = $messageLogWriterService;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     *
     * @ignoreCodeCoverage
     */
    public function addMessageToQueue(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        $getRequest = $request->getParsedBody();

        (new MessageQueueAddRequestValidator())->validate($getRequest);

        $messageService = new MessageService();
        $message        = $messageService->getMessageFromRequest($getRequest);

        $messageStoreService = new MessageStoreService(
            $this->emailComposer,
            $this->logger,
            $this->messageStoreReaderService,
            $this->messageStoreWriterService
        );
        $messageStore = $messageStoreService->addMessageToMessageStore($message);

        $messageLogService = new MessageLogService(
            $this->logger,
            $this->messageLogReaderService,
            $this->messageLogWriterService
        );
        $messageLog = $messageLogService->addMessageToMessageLog($message, $messageStore);

        $messageQueueBuilder = new MessageQueueBuilder();
        $messageQueue        = $messageQueueBuilder->buildMessageQueueFromMessageLog($messageLog);

        // TODO: Implement repository to store MessageQueue. Return with the status.

        /** @var \Slim\Http\Response $response */
        $response = $response->withJson([
                'status' => 0,
                'statusMessage' => 'Queued.',
                'message' => $message,
                'messageStore' => $messageStore,
                'messageLog' => $messageLog,
                'messageQueue' => $messageQueue,
            ]);

        return $response;
    }
}