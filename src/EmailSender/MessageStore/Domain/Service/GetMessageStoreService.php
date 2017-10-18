<?php

namespace EmailSender\MessageStore\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageStore\Domain\Builder\MessageStoreBuilder;
use EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryReaderInterface;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;

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
     * @var \EmailSender\MessageStore\Domain\Builder\MessageStoreBuilder
     */
    private $messageStoreBuilder;

    /**
     * GetMessageStoreService constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryReaderInterface $repositoryReader
     * @param \EmailSender\MessageStore\Domain\Builder\MessageStoreBuilder                    $messageStoreBuilder
     */
    public function __construct(
        MessageStoreRepositoryReaderInterface $repositoryReader,
        MessageStoreBuilder $messageStoreBuilder
    ) {
        $this->repositoryReader    = $repositoryReader;
        $this->messageStoreBuilder = $messageStoreBuilder;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageId
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     * @throws \Error
     * @throws \InvalidArgumentException
     */
    public function readByMessageId(UnsignedInteger $messageId): MessageStore
    {
        $messageStoreArray = $this->repositoryReader->readByMessageId($messageId);

        return $this->messageStoreBuilder->buildMessageStoreFromArray($messageStoreArray);
    }
}
