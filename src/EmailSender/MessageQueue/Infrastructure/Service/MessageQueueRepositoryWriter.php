<?php

namespace EmailSender\MessageQueue\Infrastructure\Service;

use EmailSender\MessageQueue\Domain\Aggregator\MessageQueue;
use EmailSender\MessageQueue\Domain\Contract\MessageQueueRepositoryWriterInterface;
use Closure;
use EmailSender\MessageQueue\Infrastructure\Builder\AMQPMessageBuilder;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Throwable;
use Error;

/**
 * Class MessageQueueRepositoryWriter
 *
 * @package EmailSender\MessageQueue
 */
class MessageQueueRepositoryWriter implements MessageQueueRepositoryWriterInterface
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
     * @var \EmailSender\MessageQueue\Infrastructure\Builder\AMQPMessageBuilder
     */
    private $amqpMessageBuilder;

    /**
     * @var string
     */
    private $queue;

    /**
     * @var string
     */
    private $exchange;

    /**
     * MessageQueueRepositoryWriter constructor.
     *
     * @param \Closure                                                            $queueService
     * @param \EmailSender\MessageQueue\Infrastructure\Builder\AMQPMessageBuilder $amqpMessageBuilder
     * @param string                                                              $queue
     * @param string                                                              $exchange
     */
    public function __construct(
        Closure $queueService,
        AMQPMessageBuilder $amqpMessageBuilder,
        string $queue,
        string $exchange
    ) {
        $this->queueService       = $queueService;
        $this->amqpMessageBuilder = $amqpMessageBuilder;
        $this->queue              = $queue;
        $this->exchange           = $exchange;
    }

    /**
     * @param \EmailSender\MessageQueue\Domain\Aggregator\MessageQueue $messageQueue
     *
     * @return bool
     * @throws \Error
     */
    public function add(MessageQueue $messageQueue): bool
    {
        try {
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

            $message = $this->amqpMessageBuilder->buildAMQPMessage($messageQueue);

            $channel->basic_publish($message, $this->exchange, $this->queue);

            $channel->close();
            $connection->close();
        } catch (Throwable $e) {

            throw new Error($e->getMessage(), $e->getCode(), $e);
        }

        return true;
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
