<?php

namespace EmailSender\MessageQueue\Domain\Service;

use EmailSender\MessageQueue\Domain\Aggregator\MessageQueue;
use EmailSender\MessageQueue\Domain\Builder\MessageQueueBuilder;
use EmailSender\MessageQueue\Domain\Contract\MessageQueueRepositoryWriterInterface;
use EmailSender\Message\Application\Service\MessageService;
use EmailSender\MessageLog\Application\Service\MessageLogService;
use EmailSender\MessageStore\Application\Service\MessageStoreService;

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
     * @var \EmailSender\Message\Application\Service\MessageService
     */
    private $messageService;

    /**
     * @var \EmailSender\MessageStore\Application\Service\MessageStoreService
     */
    private $messageStoreService;

    /**
     * @var \EmailSender\MessageLog\Application\Service\MessageLogService
     */
    private $messageLogService;

    /**
     * AddMessageQueueService constructor.
     *
     * @param \EmailSender\MessageQueue\Domain\Contract\MessageQueueRepositoryWriterInterface $queueWriter
     * @param \EmailSender\Message\Application\Service\MessageService                         $messageService
     * @param \EmailSender\MessageStore\Application\Service\MessageStoreService               $messageStoreService
     * @param \EmailSender\MessageLog\Application\Service\MessageLogService                   $messageLogService
     */
    public function __construct(
        MessageQueueRepositoryWriterInterface $queueWriter,
        MessageService $messageService,
        MessageStoreService $messageStoreService,
        MessageLogService $messageLogService
    ) {
        $this->queueWriter         = $queueWriter;
        $this->messageService      = $messageService;
        $this->messageStoreService = $messageStoreService;
        $this->messageLogService   = $messageLogService;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\MessageQueue\Domain\Aggregator\MessageQueue
     */
    public function add(array $request): MessageQueue
    {
        $message      = $this->messageService->getMessageFromRequest($request);
        $messageStore = $this->messageStoreService->addMessageToMessageStore($message);
        $messageLog   = $this->messageLogService->addMessageToMessageLog($message, $messageStore);

        $messageQueueBuilder = new MessageQueueBuilder();

        $messageQueue = $messageQueueBuilder->buildMessageQueueFromMessageLog($messageLog);

        $this->queueWriter->add($messageQueue);

        /** TODO update MessageLog queued field in the repository */

        return $messageQueue;
    }
}
