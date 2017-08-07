<?php

namespace EmailSender\MessageQueue\Domain\Service;

use EmailSender\MessageLog\Domain\Aggregator\MessageLog;
use EmailSender\MessageQueue\Domain\Aggregator\MessageQueue;
use EmailSender\MessageQueue\Domain\Builder\MessageQueueBuilder;
use EmailSender\MessageQueue\Domain\Contract\MessageQueueRepositoryWriterInterface;

/**
 * Class AddMessageQueueService
 *
 * @package EmailSender\MessageQueue
 */
class AddMessageQueueService
{
    /**
     * @var \EmailSender\MessageQueue\Domain\Contract\MessageQueueRepositoryWriterInterface
     */
    private $queueWriter;

    /**
     * @var \EmailSender\MessageQueue\Domain\Builder\MessageQueueBuilder
     */
    private $messageQueueBuilder;

    /**
     * AddMessageQueueService constructor.
     *
     * @param \Closure                                                     $queueService
     * @param \EmailSender\MessageQueue\Domain\Builder\MessageQueueBuilder $messageQueueBuilder
     */
    /**
     * AddMessageQueueService constructor.
     *
     * @param \EmailSender\MessageQueue\Domain\Contract\MessageQueueRepositoryWriterInterface $queueWriter
     * @param \EmailSender\MessageQueue\Domain\Builder\MessageQueueBuilder                    $messageQueueBuilder
     */
    public function __construct(
        MessageQueueRepositoryWriterInterface $queueWriter,
        MessageQueueBuilder $messageQueueBuilder
    ) {
        $this->queueWriter         = $queueWriter;
        $this->messageQueueBuilder = $messageQueueBuilder;
    }

    /**
     * @param \EmailSender\MessageLog\Domain\Aggregator\MessageLog $messageLog
     *
     * @return \EmailSender\MessageQueue\Domain\Aggregator\MessageQueue
     */
    public function add(MessageLog $messageLog): MessageQueue
    {
        $messageQueue = $this->messageQueueBuilder->buildMessageQueueFromMessageLog($messageLog);

        $this->queueWriter->add($messageQueue);

        return $messageQueue;
    }
}
