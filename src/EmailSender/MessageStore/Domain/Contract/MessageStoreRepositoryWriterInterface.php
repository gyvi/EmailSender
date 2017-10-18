<?php

namespace EmailSender\MessageStore\Domain\Contract;

use EmailSender\MessageStore\Domain\Aggregate\MessageStore;

/**
 * Interface MessageStoreRepositoryWriterInterface
 *
 * @package EmailSender\MessageStore
 */
interface MessageStoreRepositoryWriterInterface
{
    /**
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @return int
     *
     * @throws \Error
     */
    public function add(MessageStore $messageStore): int;
}
