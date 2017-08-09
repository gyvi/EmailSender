<?php

namespace EmailSender\MessageStore\Application\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Application\Contract\MessageStoreServiceInterface;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Service\AddMessageStoreService;
use EmailSender\MessageStore\Domain\Service\GetMessageStoreService;
use EmailSender\MessageStore\Infrastructure\Persistence\MessageStoreRepositoryReader;
use EmailSender\MessageStore\Infrastructure\Persistence\MessageStoreRepositoryWriter;
use EmailSender\MessageStore\Infrastructure\Service\EmailComposer;
use EmailSender\Recipients\Application\Service\RecipientsService;
use Closure;
use Psr\Log\LoggerInterface;
use EmailSender\MessageStore\Domain\Builder\MessageStoreBuilder;
use PHPMailer;

/**
 * Class MessageStoreService
 *
 * @package EmailSender\MessageStore
 */
class MessageStoreService implements MessageStoreServiceInterface
{
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
     * @param \Psr\Log\LoggerInterface                                         $logger
     * @param \Closure                                                         $messageStoreReaderService
     * @param \Closure                                                         $messageStoreWriterService
     */
    public function __construct(
        LoggerInterface $logger,
        Closure $messageStoreReaderService,
        Closure $messageStoreWriterService
    ) {
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
        $phpMailer              = new PHPMailer();
        $emailComposer          = new EmailComposer($phpMailer);
        $recipientsService      = new RecipientsService();
        $messageStoreBuilder    = new MessageStoreBuilder($emailComposer, $recipientsService);
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
        $phpMailer              = new PHPMailer();
        $emailComposer          = new EmailComposer($phpMailer);
        $recipientsService      = new RecipientsService();
        $messageStoreBuilder    = new MessageStoreBuilder($emailComposer, $recipientsService);
        $getMessageStoreService = new GetMessageStoreService($this->repositoryReader, $messageStoreBuilder);

        return $getMessageStoreService->readByMessageId($messageId);
    }
}
