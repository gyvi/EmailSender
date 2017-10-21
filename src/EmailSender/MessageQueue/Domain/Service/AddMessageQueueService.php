<?php

namespace EmailSender\MessageQueue\Domain\Service;

use EmailSender\Message\Application\Contract\MessageServiceInterface;
use EmailSender\MessageLog\Application\Catalog\MessageLogStatuses;
use EmailSender\MessageLog\Application\Contract\MessageLogServiceInterface;
use EmailSender\MessageLog\Application\ValueObject\MessageLogStatus;
use EmailSender\MessageQueue\Domain\Aggregator\MessageQueue;
use EmailSender\MessageQueue\Domain\Factory\MessageQueueFactory;
use EmailSender\MessageQueue\Domain\Contract\MessageQueueRepositoryWriterInterface;
use EmailSender\MessageStore\Application\Contract\MessageStoreServiceInterface;

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
     * @var \EmailSender\Message\Application\Contract\MessageServiceInterface
     */
    private $messageService;

    /**
     * @var \EmailSender\MessageStore\Application\Contract\MessageStoreServiceInterface
     */
    private $messageStoreService;

    /**
     * @var \EmailSender\MessageLog\Application\Contract\MessageLogServiceInterface
     */
    private $messageLogService;

    /**
     * @var \EmailSender\MessageQueue\Domain\Factory\MessageQueueFactory
     */
    private $messageQueueBuilder;

    /**
     * AddMessageQueueService constructor.
     *
     * @param \EmailSender\MessageQueue\Domain\Contract\MessageQueueRepositoryWriterInterface $queueWriter
     * @param \EmailSender\Message\Application\Contract\MessageServiceInterface               $messageService
     * @param \EmailSender\MessageStore\Application\Contract\MessageStoreServiceInterface     $messageStoreService
     * @param \EmailSender\MessageLog\Application\Contract\MessageLogServiceInterface         $messageLogService
     * @param \EmailSender\MessageQueue\Domain\Factory\MessageQueueFactory                    $messageQueueBuilder
     */
    public function __construct(
        MessageQueueRepositoryWriterInterface $queueWriter,
        MessageServiceInterface $messageService,
        MessageStoreServiceInterface $messageStoreService,
        MessageLogServiceInterface $messageLogService,
        MessageQueueFactory $messageQueueBuilder
    ) {
        $this->queueWriter         = $queueWriter;
        $this->messageService      = $messageService;
        $this->messageStoreService = $messageStoreService;
        $this->messageLogService   = $messageLogService;
        $this->messageQueueBuilder = $messageQueueBuilder;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\MessageQueue\Domain\Aggregator\MessageQueue
     * @throws \Error
     * @throws \InvalidArgumentException
     * @throws \phpmailerException
     */
    public function add(array $request): MessageQueue
    {
        $message      = $this->messageService->getMessageFromRequest($request);
        $messageStore = $this->messageStoreService->addMessageToMessageStore($message);
        $messageLog   = $this->messageLogService->addMessageToMessageLog($message, $messageStore);

        $messageQueue = $this->messageQueueBuilder->createFromMessageLog($messageLog);

        $this->queueWriter->add($messageQueue);

        $this->messageLogService->setStatus(
            $messageQueue->getMessageId(),
            new MessageLogStatus(MessageLogStatuses::STATUS_QUEUED),
            ''
        );

        return $messageQueue;
    }
}
