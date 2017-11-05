<?php

namespace EmailSender\EmailQueue\Application\Service;

use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailQueue\Application\Contract\EmailQueueServiceInterface;
use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;
use EmailSender\EmailQueue\Domain\Service\AddEmailQueueService;
use EmailSender\EmailQueue\Infrastructure\Factory\AMQPMessageFactory;
use EmailSender\EmailQueue\Infrastructure\Service\EmailQueueRepositoryWriter;
use Closure;
use Psr\Log\LoggerInterface;
use EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory;

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
        $this->logger                     = $logger;
        $this->queueService               = $queueService;
        $this->queueServiceSettings       = $queueServiceSettings;
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog $emailLog
     *
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue
     *
     * @throws \Error
     */
    public function add(EmailLog $emailLog): EmailQueue
    {
        $amqpMessageFactory = new AMQPMessageFactory();
        $queueWriter        = new EmailQueueRepositoryWriter(
            $this->queueService,
            $amqpMessageFactory,
            $this->queueServiceSettings['queue'],
            $this->queueServiceSettings['exchange']
        );

        $emailQueueFactory = new EmailQueueFactory();

        $addEmailQueueService = new AddEmailQueueService($queueWriter, $emailQueueFactory);

        return $addEmailQueueService->add($emailLog);
    }
}
