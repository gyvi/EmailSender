<?php

namespace EmailSender\MessageStore\Domain\Builder;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
use EmailSender\MessageStore\Infrastructure\Persistence\MessageStoreFieldList;
use EmailSender\Recipients\Application\Service\RecipientsService;
use EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryReaderInterface;

/**
 * Class MessageStoreBuilder
 *
 * @package EmailSender\MessageStore
 */
class MessageStoreBuilder
{
    /**
     * @var \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface
     */
    private $emailComposer;

    /**
     * @var \EmailSender\Recipients\Application\Service\RecipientsService
     */
    private $recipientsService;

    /**
     * @var \EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryReaderInterface
     */
    private $repositoryReader;

    /**
     * MessageStoreBuilder constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface                $emailComposer
     * @param \EmailSender\Recipients\Application\Service\RecipientsService                   $recipientsService
     * @param \EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryReaderInterface $repositoryReader
     */
    public function __construct(
        EmailComposerInterface $emailComposer,
        RecipientsService $recipientsService,
        MessageStoreRepositoryReaderInterface $repositoryReader
    ) {
        $this->emailComposer     = $emailComposer;
        $this->recipientsService = $recipientsService;
        $this->repositoryReader  = $repositoryReader;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function buildMessageStoreFromMessage(Message $message): MessageStore
    {
        $recipients      = $this->recipientsService->getRecipientsFromMessage($message);
        $renderedMessage = $this->emailComposer->composeEmailFromMessage($message);

        return new MessageStore($recipients, $renderedMessage);
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function buildMessageStoreFromRepository(UnsignedInteger $messageId): MessageStore
    {
        $messageStoreArray = $this->repositoryReader->readByMessageId($messageId);

        $recipients = $this->recipientsService->getRecipientsFromArray(
            json_decode($messageStoreArray[MessageStoreFieldList::RECIPIENTS_FIELD], true)
        );

        $messageStore = new MessageStore(
            $recipients,
            new StringLiteral($messageStoreArray[MessageStoreFieldList::MESSAGE_FIELD])
        );

        $messageStore->setMessageId(new UnsignedInteger($messageStoreArray[MessageStoreFieldList::MESSAGE_ID_FIELD]));

        return $messageStore;
    }
}
