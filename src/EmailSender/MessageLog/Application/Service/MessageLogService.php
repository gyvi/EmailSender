<?php

namespace EmailSender\MessageLog\Application\Service;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\MailAddress\Application\Service\MailAddressService;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageLog\Application\Contract\MessageLogServiceInterface;
use EmailSender\MessageLog\Application\ValueObject\MessageLogStatus;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageLog\Domain\Service\AddMessageLogService;
use EmailSender\MessageLog\Domain\Service\GetMessageLogService;
use EmailSender\MessageLog\Domain\Service\UpdateMessageLogService;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\Recipients\Application\Service\RecipientsService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use Closure;
use Psr\Log\LoggerInterface;
use EmailSender\MessageLog\Infrastructure\Persistence\MessageLogRepositoryReader;
use EmailSender\MessageLog\Infrastructure\Persistence\MessageLogRepositoryWriter;
use EmailSender\MessageLog\Domain\Builder\MessageLogBuilder;

/**
 * Class MessageLogService
 *
 * @package EmailSender\MessageLog\Application\Service
 */
class MessageLogService implements MessageLogServiceInterface
{
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
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Closure                 $messageLogReaderService
     * @param \Closure                 $messageLogWriterService
     */
    public function __construct(
        LoggerInterface $logger,
        Closure $messageLogReaderService,
        Closure $messageLogWriterService
    ) {
        $this->logger           = $logger;
        $this->repositoryReader = new MessageLogRepositoryReader($messageLogReaderService);
        $this->repositoryWriter = new MessageLogRepositoryWriter($messageLogWriterService);
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
    public function listMessagesFromLog(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $getRequest
    ): MessageInterface {
        $response->getBody()->write('listMessagesFromLog');

        return $response;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message           $message
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @return \EmailSender\MessageLog\Domain\Aggregate\MessageLog
     */
    public function addMessageToMessageLog(Message $message, MessageStore $messageStore): MessageLog
    {
        $recipientsService  = new RecipientsService();
        $mailAddressService = new MailAddressService();
        $messageLogBuilder  = new MessageLogBuilder($recipientsService, $mailAddressService);

        $addMessageLogService = new AddMessageLogService($this->repositoryWriter, $messageLogBuilder);

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
        ?string $errorMessageString): void
    {
        $errorMessage = new StringLiteral((string)$errorMessageString);
        $updateMessageLogService = new UpdateMessageLogService($this->repositoryWriter);
        $updateMessageLogService->setStatus($messageLogId, $messageLogStatus, $errorMessage);
    }

    /**
     * @param int $messageLogIdInt
     *
     * @return \EmailSender\MessageLog\Domain\Aggregate\MessageLog
     */
    public function getMessageLogFromRepository(int $messageLogIdInt): MessageLog
    {
        $messageLogId       = new UnsignedInteger($messageLogIdInt);
        $recipientsService  = new RecipientsService();
        $mailAddressService = new MailAddressService();
        $messageLogBuilder  = new MessageLogBuilder($recipientsService, $mailAddressService);

        $getMessageLogService = new GetMessageLogService($this->repositoryReader, $messageLogBuilder);

        return $getMessageLogService->readByMessageLogId($messageLogId);
    }
}
