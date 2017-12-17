<?php

namespace EmailSender\EmailQueue\Infrastructure\Service;

use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;
use EmailSender\EmailQueue\Domain\Contract\EmailQueueRepositoryWriterInterface;
use Closure;
use EmailSender\EmailQueue\Infrastructure\Factory\AMQPMessageFactory;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class EmailQueueRepositoryWriter
 *
 * @package EmailSender\EmailQueue
 */
class EmailQueueRepositoryWriter implements EmailQueueRepositoryWriterInterface
{
    /**
     * @var \Closure
     */
    private $queueService;

    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @var \EmailSender\EmailQueue\Infrastructure\Factory\AMQPMessageFactory
     */
    private $amqpMessageFactory;

    /**
     * @var string
     */
    private $queue;

    /**
     * @var string
     */
    private $exchange;

    /**
     * EmailQueueRepositoryWriter constructor.
     *
     * @param \Closure                                                          $queueService
     * @param \EmailSender\EmailQueue\Infrastructure\Factory\AMQPMessageFactory $amqpMessageFactory
     * @param string                                                            $queue
     * @param string                                                            $exchange
     */
    public function __construct(
        Closure $queueService,
        AMQPMessageFactory $amqpMessageFactory,
        string $queue,
        string $exchange
    ) {
        $this->queueService       = $queueService;
        $this->amqpMessageFactory = $amqpMessageFactory;
        $this->queue              = $queue;
        $this->exchange           = $exchange;
    }

    /**
     * @param \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue $emailQueue
     *
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue
     */
    public function add(EmailQueue $emailQueue): EmailQueue
    {
        $connection = $this->getConnection();
        $channel    = $connection->channel();

        $channel->exchange_declare(
            $this->exchange,
            'x-delayed-message',
            false,  /* Passive, create if exchange doesn't exist */
            true,   /* Durable, persist through server reboots */
            false,  /* Auto delete */
            false,  /* Internal */
            false,  /* Nowait */
            ['x-delayed-type' => ['S', 'direct']]
        );

        $channel->queue_declare(
            $this->queue,
            false,  /* Passive */
            true,   /* Durable */
            false,  /* Exclusive */
            false   /* Auto Delete */
        );

        $channel->queue_bind($this->queue, $this->exchange, $this->queue);

        $message = $this->amqpMessageFactory->create($emailQueue);

        $channel->basic_publish($message, $this->exchange, $this->queue);

        $channel->close();
        $connection->close();

        return $emailQueue;
    }

    /**
     * @return \PhpAmqpLib\Connection\AMQPStreamConnection
     */
    private function getConnection(): AMQPStreamConnection
    {
        if (!$this->connection) {
            $this->connection = ($this->queueService)();
        }

        return $this->connection;
    }
}
