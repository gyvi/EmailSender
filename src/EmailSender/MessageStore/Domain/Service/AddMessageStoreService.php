<?php

namespace EmailSender\MessageStore\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Builder\MessageStoreBuilder;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
use EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryWriterInterface;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Recipients\Application\Service\RecipientsService;

/**
 * Class AddMessageStoreService
 *
 * @package EmailSender\MessageStore
 */
class AddMessageStoreService
{
    /**
     * @var \EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryWriterInterface
     */
    private $repositoryWriter;

    /**
     * @var \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface
     */
    private $emailComposer;

    /**
     * @var \EmailSender\Recipients\Application\Service\RecipientsService
     */
    private $recipientsService;

    /**
     * AddMessageStoreService constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryWriterInterface $repositoryWriter
     * @param \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface                $emailComposer
     * @param \EmailSender\Recipients\Application\Service\RecipientsService                   $recipientsService
     */
    public function __construct(
        MessageStoreRepositoryWriterInterface $repositoryWriter,
        EmailComposerInterface $emailComposer,
        RecipientsService $recipientsService
    ) {
        $this->repositoryWriter  = $repositoryWriter;
        $this->emailComposer     = $emailComposer;
        $this->recipientsService = $recipientsService;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function add(Message $message): MessageStore
    {
        $messageStoreBuilder = new MessageStoreBuilder($this->emailComposer, $this->recipientsService);
        $messageStore        = $messageStoreBuilder->buildMessageStoreFromMessage($message);
        $messageStoreId      = new UnsignedInteger($this->repositoryWriter->add($messageStore));

        $messageStore->setMessageId($messageStoreId);

        return $messageStore;
    }
}
