<?php

namespace EmailSender\MessageQueue\Infrastructure\Service;

use EmailSender\MessageQueue\Domain\Aggregator\MessageQueue;
use EmailSender\MessageQueue\Domain\Contract\MessageQueueRepositoryWriterInterface;
use Closure;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
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
     * MessageQueueRepositoryWriter constructor.
     *
     * @param \Closure $queueService
     */
    public function __construct(Closure $queueService)
    {
        $this->queueService = $queueService;
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

            $channel->queue_declare('emailSender', false, true, false, true);

            $message = new AMQPMessage(json_encode($messageQueue));
            $channel->basic_publish($message);

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
