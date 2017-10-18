<?php

namespace EmailSender\MessageStore\Domain\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

/**
 * Class MessageStoreRepositoryReaderInterface
 *
 * @package EmailSender\MessageStore
 */
interface MessageStoreRepositoryReaderInterface
{
    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     *
     * @return array
     *
     * @throws \Error
     */
    public function readByMessageId(UnsignedInteger $messageId): array;
}
