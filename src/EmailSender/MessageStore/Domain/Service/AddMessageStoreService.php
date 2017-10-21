<?php

namespace EmailSender\MessageStore\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Factory\MessageStoreFactory;
use EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryWriterInterface;
use EmailSender\Message\Domain\Aggregate\Message;

/**
 * Class AddMessageStoreService
 *
 * @package EmailSender\MessageStore
 */
class AddMessageStoreService
{
    /**
     * @var \EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryWriterInterface
     */
    private $repositoryWriter;

    /**
     * @var \EmailSender\MessageStore\Domain\Factory\MessageStoreFactory
     */
    private $messageStoreBuilder;

    /**
     * AddMessageStoreService constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryWriterInterface $repositoryWriter
     * @param \EmailSender\MessageStore\Domain\Factory\MessageStoreFactory                    $messageStoreBuilder
     */
    public function __construct(
        MessageStoreRepositoryWriterInterface $repositoryWriter,
        MessageStoreFactory $messageStoreBuilder
    ) {
        $this->repositoryWriter    = $repositoryWriter;
        $this->messageStoreBuilder = $messageStoreBuilder;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     * @throws \Error
     * @throws \InvalidArgumentException
     * @throws \phpmailerException
     */
    public function add(Message $message): MessageStore
    {
        $messageStore   = $this->messageStoreBuilder->createFromMessage($message);
        $messageStoreId = $this->repositoryWriter->add($messageStore);

        $messageStore->setMessageId(new UnsignedInteger($messageStoreId));

        return $messageStore;
    }
}
