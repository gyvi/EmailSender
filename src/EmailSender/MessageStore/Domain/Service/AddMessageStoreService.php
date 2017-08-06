<?php

namespace EmailSender\MessageStore\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryWriterInterface;

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
     * AddMessageStoreService constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\MessageStoreRepositoryWriterInterface $repositoryWriter
     */
    public function __construct(MessageStoreRepositoryWriterInterface $repositoryWriter)
    {
        $this->repositoryWriter = $repositoryWriter;
    }

    /**
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     */
    public function add(MessageStore $messageStore): UnsignedInteger
    {
        return new UnsignedInteger($this->repositoryWriter->add($messageStore));
    }
}
