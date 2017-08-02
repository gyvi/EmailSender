<?php

namespace EmailSender\MessageStore\Application\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Application\Contract\MessageStoreServiceInterface;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Builder\MessageStoreBuilder;
use EmailSender\MessageStore\Domain\Contract\EmailBuilderInterface;

/**
 * Class MessageStoreService
 *
 * @package EmailSender\MessageStore
 */
class MessageStoreService implements MessageStoreServiceInterface
{
    /**
     * @var \EmailSender\MessageStore\Domain\Contract\EmailBuilderInterface
     */
    private $emailBuilder;

    public function __construct(EmailBuilderInterface $emailBuilder)
    {
        $this->emailBuilder = $emailBuilder;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function addMessageToMessageStore(Message $message): MessageStore
    {
        $messageStoreBuilder = new MessageStoreBuilder($this->emailBuilder);

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