<?php

namespace EmailSender\MessageLog\Domain\Factory;

use EmailSender\MessageLog\Application\Collection\MessageLogCollection;

/**
 * Class MessageLogCollectionFactory
 *
 * @package EmailSender\MessageLog
 */
class MessageLogCollectionFactory
{
    /**
     * @var \EmailSender\MessageLog\Domain\Factory\MessageLogFactory
     */
    private $messageLogBuilder;

    /**
     * MessageLogCollectionBuilder constructor.
     *
     * @param \EmailSender\MessageLog\Domain\Factory\MessageLogFactory $messageLogBuilder
     */
    public function __construct(MessageLogFactory $messageLogBuilder)
    {
        $this->messageLogBuilder = $messageLogBuilder;
    }

    /**
     * @param array $messageLogCollectionArray
     *
     * @return \EmailSender\MessageLog\Application\Collection\MessageLogCollection
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function create(array $messageLogCollectionArray): MessageLogCollection
    {
        $messageLogCollection = new MessageLogCollection();

        /** @var array $messageLogArray */
        foreach ($messageLogCollectionArray as $messageLogArray) {
            $messageLogCollection->add($this->messageLogBuilder->createFromArray($messageLogArray));
        }

        return $messageLogCollection;
    }
}
