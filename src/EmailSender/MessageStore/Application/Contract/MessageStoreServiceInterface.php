<?php

namespace EmailSender\MessageStore\Application\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;

/**
 * Interface MessageStoreServiceInterface
 *
 * @package EmailSender\MessageStore
 */
interface MessageStoreServiceInterface
{
    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     * @throws \Error
     * @throws \InvalidArgumentException
     * @throws \phpmailerException
     */
    public function addMessageToMessageStore(Message $message): MessageStore;

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     * @throws \Error
     * @throws \InvalidArgumentException
     */
    public function getMessageStoreFromRepository(UnsignedInteger $messageId): MessageStore;
}
