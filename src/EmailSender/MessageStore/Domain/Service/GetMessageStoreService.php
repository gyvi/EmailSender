<?php

namespace EmailSender\MessageStore\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryReaderInterface;

/**
 * Class GetMessageStoreService
 *
 * @package EmailSender\MessageStore
 */
class GetMessageStoreService
{
    /**
     * @var \EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryReaderInterface
     */
    private $repositoryReader;

    /**
     * GetMessageStoreService constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryReaderInterface $repositoryReader
     */
    public function __construct(MessageStoreRepositoryReaderInterface $repositoryReader)
    {
        $this->repositoryReader = $repositoryReader;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function readByMessageId(UnsignedInteger $messageId): MessageStore
    {
        return $this->repositoryReader->readByMessageId($messageId);
    }
}
