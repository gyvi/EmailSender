<?php

namespace EmailSender\MessageStore\Application\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Application\Contract\MessageStoreServiceInterface;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
use EmailSender\MessageStore\Domain\Service\AddMessageStoreService;
use EmailSender\MessageStore\Domain\Service\GetMessageStoreService;
use EmailSender\MessageStore\Infrastructure\Persistence\MessageStoreRepositoryReader;
use EmailSender\MessageStore\Infrastructure\Persistence\MessageStoreRepositoryWriter;
use EmailSender\Recipients\Application\Service\RecipientsService;
use Closure;
use Psr\Log\LoggerInterface;
use EmailSender\MessageStore\Domain\Builder\MessageStoreBuilder;

/**
 * Class MessageStoreService
 *
 * @package EmailSender\MessageStore
 */
class MessageStoreService implements MessageStoreServiceInterface
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
     * @var \EmailSender\MessageStore\Infrastructure\Persistence\MessageStoreRepositoryReader
     */
    private $repositoryReader;

    /**
     * @var \EmailSender\MessageStore\Infrastructure\Persistence\MessageStoreRepositoryWriter
     */
    private $repositoryWriter;

    /**
     * MessageStoreService constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface $emailComposer
     * @param \Psr\Log\LoggerInterface                                         $logger
     * @param \Closure                                                         $messageStoreReaderService
     * @param \Closure                                                         $messageStoreWriterService
     */
    public function __construct(
        EmailComposerInterface $emailComposer,
        LoggerInterface $logger,
        Closure $messageStoreReaderService,
        Closure $messageStoreWriterService
    ) {
        $this->emailComposer    = $emailComposer;
        $this->logger           = $logger;
        $this->repositoryReader = new MessageStoreRepositoryReader($messageStoreReaderService);
        $this->repositoryWriter = new MessageStoreRepositoryWriter($messageStoreWriterService);
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function addMessageToMessageStore(Message $message): MessageStore
    {
        $recipientsService      = new RecipientsService();
        $messageStoreBuilder    = new MessageStoreBuilder($this->emailComposer, $recipientsService);
        $addMessageStoreService = new AddMessageStoreService($this->repositoryWriter, $messageStoreBuilder);

        return $addMessageStoreService->add($message);
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function getMessageStoreFromRepository(UnsignedInteger $messageId): MessageStore
    {
        $recipientsService      = new RecipientsService();
        $messageStoreBuilder    = new MessageStoreBuilder($this->emailComposer, $recipientsService);
        $getMessageStoreService = new GetMessageStoreService($this->repositoryReader, $messageStoreBuilder);

        return $getMessageStoreService->readByMessageId($messageId);
    }
}
