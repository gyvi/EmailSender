<?php

namespace EmailSender\MessageStore\Domain\Builder;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
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
        $this->emailComposer     = $emailComposer;
        $this->recipientsService = $recipientsService;
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
     * @param int    $messageId
     * @param string $recipients
     * @param string $message
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function buildMessageStoreFromRepository(int $messageId, string $recipients, string $message): MessageStore
    {
        $recipients = $this->recipientsService->getRecipientsFromArray(json_decode($recipients));

        $messageStore = new MessageStore(
            $recipients,
            new StringLiteral($message)
        );

        $messageStore->setMessageId(new UnsignedInteger($messageId));

        return $messageStore;
    }
}
