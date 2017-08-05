<?php

namespace EmailSender\MessageStore\Domain\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Message\Domain\Aggregate\Message;

/**
 * Class EmailBuilderInterface
 *
 * @package EmailSender\MessageStore
 */
interface MessageStoreBuilderInterface
{
    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral
     */
    public function buildMessageStoreFromMessage(Message $message): StringLiteral;
}
