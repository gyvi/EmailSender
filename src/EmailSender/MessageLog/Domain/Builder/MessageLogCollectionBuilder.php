<?php

namespace EmailSender\MessageLog\Domain\Builder;

use EmailSender\MessageLog\Application\Collection\MessageLogCollection;

/**
 * Class MessageLogCollectionBuilder
 *
 * @package EmailSender\MessageLog
 */
class MessageLogCollectionBuilder
{
    /**
     * @var \EmailSender\MessageLog\Domain\Builder\MessageLogBuilder
     */
    private $messageLogBuilder;

    /**
     * MessageLogCollectionBuilder constructor.
     *
     * @param \EmailSender\MessageLog\Domain\Builder\MessageLogBuilder $messageLogBuilder
     */
    public function __construct(MessageLogBuilder $messageLogBuilder)
    {
        $this->messageLogBuilder = $messageLogBuilder;
    }

    /**
     * @param array $messageLogCollectionArray
     *
     * @return \EmailSender\MessageLog\Application\Collection\MessageLogCollection
     */
    public function buildMessageLogCollectionFromArray(array $messageLogCollectionArray): MessageLogCollection
    {
        $messageLogCollection = new MessageLogCollection();

        /** @var array $messageLogArray */
        foreach ($messageLogCollectionArray as $messageLogArray) {
            $messageLogCollection->add($this->messageLogBuilder->buildMessageLogFromArray($messageLogArray));
        }

        return $messageLogCollection;
    }
}
