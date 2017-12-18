<?php

namespace EmailSender\EmailQueue\Application\Service;

use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailQueue\Application\Contract\EmailQueueServiceInterface;
use EmailSender\EmailQueue\Application\Exception\EmailQueueException;
use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;
use EmailSender\EmailQueue\Domain\Service\AddEmailQueueService;
use EmailSender\EmailQueue\Domain\Service\GetEmailQueueService;
use EmailSender\EmailQueue\Infrastructure\Factory\AMQPMessageFactory;
use EmailSender\EmailQueue\Infrastructure\Service\EmailQueueRepositoryWriter;
use Closure;
use Psr\Log\LoggerInterface;
use EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory;
use Throwable;

/**
 * Class EmailQueueService
 *
 * @package EmailSender\EmailQueue\Application\Service
 */
class EmailQueueService implements EmailQueueServiceInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Closure
     */
    private $queueService;

    /**
     * @var array
     */
    private $queueServiceSettings;

    /**
     * EmailQueueService constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Closure                 $queueService
     * @param array                    $queueServiceSettings
     */
    public function __construct(
        LoggerInterface $logger,
        Closure $queueService,
        array $queueServiceSettings
    ) {
        $this->logger               = $logger;
        $this->queueService         = $queueService;
        $this->queueServiceSettings = $queueServiceSettings;
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog $emailLog
     *
     * @return \EmailSender\Core\ValueObject\EmailStatus
     *
     * @throws \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     */
    public function add(EmailLog $emailLog): EmailStatus
    {
        try {
            $amqpMessageFactory = new AMQPMessageFactory();
            $queueWriter        = new EmailQueueRepositoryWriter(
                $this->queueService,
                $amqpMessageFactory,
                $this->queueServiceSettings['queue'],
                $this->queueServiceSettings['exchange']
            );

            $emailQueueFactory    = new EmailQueueFactory();
            $addEmailQueueService = new AddEmailQueueService($queueWriter, $emailQueueFactory);

            $addEmailQueueService->add($emailLog);
        } catch (Throwable $e) {
            $this->logger->alert($e->getMessage(), $e->getTrace());

            throw new EmailQueueException('Something went wrong with the queue.', 0, $e);
        }

        return new EmailStatus(EmailStatusList::QUEUED);
    }

    /**
     * @param array $emailQueueArray
     *
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue
     *
     * @throws \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     */
    public function get(array $emailQueueArray): EmailQueue
    {
        $emailQueueFactory    = new EmailQueueFactory();
        $getEmailQueueService = new GetEmailQueueService($emailQueueFactory);

        try {
            $emailQueue = $getEmailQueueService->get($emailQueueArray);
        } catch (Throwable $e) {
            $this->logger->alert($e->getMessage(), $e->getTrace());

            throw new EmailQueueException('Invalid email queue.', 0, $e);
        }

        return $emailQueue;
    }
}
