<?php

namespace EmailSender\MessageStore\Domain\Builder;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
use EmailSender\MessageStore\Infrastructure\Persistence\MessageStoreFieldList;
use EmailSender\Recipients\Application\Service\RecipientsService;

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
     * MessageStoreBuilder constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface $emailComposer
     * @param \EmailSender\Recipients\Application\Service\RecipientsService    $recipientsService
     */
    public function __construct(EmailComposerInterface $emailComposer, RecipientsService $recipientsService)
    {
        $this->emailComposer          = $emailComposer;
        $this->recipientsService      = $recipientsService;
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
     * @param array $messageStoreArray
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function buildMessageStoreFromArray(array $messageStoreArray): MessageStore
    {
        $recipients = $this->recipientsService->getRecipientsFromJson(
            $messageStoreArray[MessageStoreFieldList::FIELD_RECIPIENTS]
        );

        $messageStore = new MessageStore(
            $recipients,
            new StringLiteral($messageStoreArray[MessageStoreFieldList::FIELD_MESSAGE])
        );

        $messageStore->setMessageId(new UnsignedInteger($messageStoreArray[MessageStoreFieldList::FIELD_MESSAGE_ID]));

        return $messageStore;
    }
}
