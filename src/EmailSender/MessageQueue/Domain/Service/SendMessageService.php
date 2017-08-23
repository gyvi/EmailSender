<?php

namespace EmailSender\MessageQueue\Domain\Service;

use EmailSender\MessageLog\Application\Contract\MessageLogServiceInterface;
use EmailSender\MessageLog\Application\ValueObject\MessageLogStatus;
use EmailSender\MessageQueue\Application\Catalog\MessageQueuePropertyList;
use EmailSender\MessageQueue\Domain\Contract\SMTPSenderInterface;
use EmailSender\MessageQueue\Infrastructure\Service\SMTPException;
use EmailSender\MessageStore\Application\Contract\MessageStoreServiceInterface;
use Throwable;
use EmailSender\MessageLog\Application\Catalog\MessageLogStatuses;

/**
 * Class SendMessageService
 *
 * @package EmailSender\MessageQueue\Domain\Service
 */
class SendMessageService
{
    /**
     * @var \EmailSender\MessageLog\Application\Contract\MessageLogServiceInterface
     */
    private $messageLogService;

    /**
     * @var \EmailSender\MessageStore\Application\Contract\MessageStoreServiceInterface
     */
    private $messageStoreService;

    /**
     * @var \EmailSender\MessageQueue\Domain\Contract\SMTPSenderInterface
     */
    private $SMTPSender;

    /**
     * SendMessageService constructor.
     *
     * @param \EmailSender\MessageLog\Application\Contract\MessageLogServiceInterface     $messageLogService
     * @param \EmailSender\MessageStore\Application\Contract\MessageStoreServiceInterface $messageStoreService
     * @param \EmailSender\MessageQueue\Domain\Contract\SMTPSenderInterface               $SMTPSender
     */
    public function __construct(
        MessageLogServiceInterface $messageLogService,
        MessageStoreServiceInterface $messageStoreService,
        SMTPSenderInterface $SMTPSender
    ) {
        $this->messageLogService   = $messageLogService;
        $this->messageStoreService = $messageStoreService;
        $this->SMTPSender          = $SMTPSender;
    }

    /**
     * @param string $messageQueueJson
     *
     * @throws \EmailSender\MessageQueue\Infrastructure\Service\SMTPException
     */
    public function send(string $messageQueueJson): void
    {
        $messageQueue = json_decode($messageQueueJson, true);

        $messageLog = $this->messageLogService->getMessageLogFromRepository(
            $messageQueue[MessageQueuePropertyList::MESSAGE_LOG_ID]
        );

        $messageStore = $this->messageStoreService->getMessageStoreFromRepository($messageLog->getMessageId());

        try {
            $this->SMTPSender->send($messageLog, $messageStore);

            $this->messageLogService->setStatus(
                $messageLog->getMessageLogId(),
                new MessageLogStatus(MessageLogStatuses::STATUS_SENT),
                ''
            );
        } catch (Throwable $e) {
            $this->messageLogService->setStatus(
                $messageLog->getMessageLogId(),
                new MessageLogStatus(MessageLogStatuses::STATUS_ERROR),
                $e->getMessage()
            );

            throw new SMTPException($e->getMessage(), 0, $e);
        }
    }
}
