<?php

namespace EmailSender\MessageStore\Application\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Application\Contract\MessageStoreServiceInterface;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Builder\MessageStoreBuilder;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
use EmailSender\Recipients\Application\Service\RecipientsService;

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
     * MessageStoreService constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface $emailComposer
     */
    public function __construct(EmailComposerInterface $emailComposer)
    {
        $this->emailComposer = $emailComposer;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function addMessageToMessageStore(Message $message): MessageStore
    {
        $recipientsService   = new RecipientsService();
        $messageStoreBuilder = new MessageStoreBuilder($this->emailComposer, $recipientsService);

        $messageStore = $messageStoreBuilder->buildMessageStoreFromMessage($message);

        // TODO: Implement repository store. Return with the messageId.
        $messageStore->setMessageId(new UnsignedInteger(1));

        return $messageStore;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function getMessageFromMessageStore(UnsignedInteger $messageId): MessageStore
    {
        // TODO: Implement getMessageFromMessageStore() method.
    }
}