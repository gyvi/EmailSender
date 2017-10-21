<?php

namespace EmailSender\MessageLog\Application\Service;

use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Core\Scalar\Application\Factory\DateTimeFactory;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageLog\Application\Contract\MessageLogServiceInterface;
use EmailSender\MessageLog\Application\Validator\MessageLogListRequestValidator;
use EmailSender\MessageLog\Application\ValueObject\MessageLogStatus;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageLog\Domain\Factory\ListMessageLogsRequestFactory;
use EmailSender\MessageLog\Domain\Factory\MessageLogCollectionFactory;
use EmailSender\MessageLog\Domain\Service\AddMessageLogService;
use EmailSender\MessageLog\Domain\Service\GetMessageLogService;
use EmailSender\MessageLog\Domain\Service\UpdateMessageLogService;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use Closure;
use Psr\Log\LoggerInterface;
use EmailSender\MessageLog\Infrastructure\Persistence\MessageLogRepositoryReader;
use EmailSender\MessageLog\Infrastructure\Persistence\MessageLogRepositoryWriter;
use EmailSender\MessageLog\Domain\Factory\MessageLogFactory;

/**
 * Class MessageLogService
 *
 * @package EmailSender\MessageLog\Application\Service
 */
class MessageLogService implements MessageLogServiceInterface
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
     * @var \EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryReaderInterface
     */
    private $repositoryReader;

    /**
     * @var \EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryWriterInterface
     */
    private $repositoryWriter;

    /**
     * MessageLogService constructor.
     *
     * @param \Closure                 $view
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Closure                 $messageLogReaderService
     * @param \Closure                 $messageLogWriterService
     */
    public function __construct(
        Closure $view,
        LoggerInterface $logger,
        Closure $messageLogReaderService,
        Closure $messageLogWriterService
    ) {
        $this->view             = $view;
        $this->logger           = $logger;
        $this->repositoryReader = new MessageLogRepositoryReader($messageLogReaderService);
        $this->repositoryWriter = new MessageLogRepositoryWriter($messageLogWriterService);
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message           $message
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @return \EmailSender\MessageLog\Domain\Aggregate\MessageLog
     */
    public function addMessageToMessageLog(Message $message, MessageStore $messageStore): MessageLog
    {
        $emailAddressFactory           = new EmailAddressFactory();
        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
        $recipientsFactory             = new RecipientsFactory($emailAddressCollectionFactory);

        $dateTimeFactory               = new DateTimeFactory();
        $messageLogFactory             = new MessageLogFactory(
            $recipientsFactory,
            $emailAddressFactory,
            $dateTimeFactory
        );

        $addMessageLogService = new AddMessageLogService($this->repositoryWriter, $messageLogFactory);

        return $addMessageLogService->add($message, $messageStore);
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageLogId
     * @param \EmailSender\MessageLog\Application\ValueObject\MessageLogStatus         $messageLogStatus
     * @param null|string                                                              $errorMessageString
     */
    public function setStatus(
        UnsignedInteger $messageLogId,
        MessageLogStatus $messageLogStatus,
        ?string $errorMessageString
    ): void {
        $errorMessage            = new StringLiteral((string)$errorMessageString);
        $updateMessageLogService = new UpdateMessageLogService($this->repositoryWriter);

        $updateMessageLogService->setStatus($messageLogId, $messageLogStatus, $errorMessage);
    }

    /**
     * @param int $messageLogIdInt
     *
     * @return \EmailSender\MessageLog\Domain\Aggregate\MessageLog
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function getMessageLogFromRepository(int $messageLogIdInt): MessageLog
    {
        $messageLogId                  = new UnsignedInteger($messageLogIdInt);
        $emailAddressFactory           = new EmailAddressFactory();
        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
        $recipientsFactory             = new RecipientsFactory($emailAddressCollectionFactory);


        $dateTimeFactory               = new DateTimeFactory();
        $messageLogFactory             = new MessageLogFactory(
            $recipientsFactory,
            $emailAddressFactory,
            $dateTimeFactory
        );

        $messageLogCollectionBuilder   = new MessageLogCollectionFactory($messageLogFactory);
        $listMessageLogsRequestBuilder = new ListMessageLogsRequestFactory($emailAddressFactory);

        $getMessageLogService = new GetMessageLogService(
            $this->repositoryReader,
            $messageLogFactory,
            $messageLogCollectionBuilder,
            $listMessageLogsRequestBuilder
        );

        return $getMessageLogService->readByMessageLogId($messageLogId);
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
    public function listMessageLogs(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        $queryParams = $request->getQueryParams();

        (new MessageLogListRequestValidator())->validate($queryParams);

        $emailAddressFactory           = new EmailAddressFactory();
        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);
        $recipientsFactory             = new RecipientsFactory($emailAddressCollectionFactory);

        $dateTimeFactory               = new DateTimeFactory();
        $messageLogBuilder             = new MessageLogFactory(
            $recipientsFactory,
            $emailAddressFactory,
            $dateTimeFactory
        );

        $messageLogCollectionBuilder   = new MessageLogCollectionFactory($messageLogBuilder);
        $listMessageLogsRequestBuilder = new ListMessageLogsRequestFactory($emailAddressFactory);

        $getMessageLogService = new GetMessageLogService(
            $this->repositoryReader,
            $messageLogBuilder,
            $messageLogCollectionBuilder,
            $listMessageLogsRequestBuilder
        );

        $messageLogList = $getMessageLogService->listMessageLogs($queryParams);

        /** @var \Slim\Http\Response $response */
        $response = $response->withJson([
            'status' => 0,
            'messages' => $messageLogList,
        ]);

        return $response;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $getRequest
     *
     * @return \Psr\Http\Message\MessageInterface
     */
    public function messageLogLister(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        /** @var \Slim\Views\Twig $twig */
        $twig = ($this->view)();

        return $twig->render($response, 'MessageLog/Application/View/messageLogLister.twig');
    }
}
