<?php

declare(strict_types=1);

require __DIR__ . '/../../../../../vendor/autoload.php';

use EmailSender\MessageQueue\Application\Service\MessageQueueService;
use EmailSender\Core\Services\ServiceList;
use EmailSender\Core\Framework\BootstrapCli;
use Slim\Container;

$settings  = require_once __DIR__ . '/../../../../EmailSender/Core/Framework/settings.php';
$container = new Container($settings);

// Bootstrap init
(new BootstrapCli($container))->init();

/** @var \Psr\Log\LoggerInterface $logger */
$logger        = $container->get(ServiceList::LOGGER);
$queueSettings = $container->get('settings')[ServiceList::QUEUE];

/** @var \PhpAmqpLib\Connection\AMQPStreamConnection $connection */
$connection = $container->get(ServiceList::QUEUE)();

/** @var \PhpAmqpLib\Channel\AMQPChannel $channel */
$channel = $connection->channel();

/** @var array $queueName */
$queueName = $queueSettings['queue'];

$callback = function ($message) use ($container, $queueSettings, $logger) {
    /** @var \PhpAmqpLib\Message\AMQPMessage $message */
    try {
        $messageQueueService = new MessageQueueService(
            $logger,
            $container->get(ServiceList::QUEUE),
            $queueSettings,
            $container->get(ServiceList::MESSAGE_STORE_READER),
            $container->get(ServiceList::MESSAGE_STORE_WRITER),
            $container->get(ServiceList::MESSAGE_LOG_READER),
            $container->get(ServiceList::MESSAGE_LOG_WRITER),
            $container->get(ServiceList::SMTP)
        );

        $messageQueueService->sendMessageFromQueue($message->body);

        $logger->notice('Sent email: ' . $message->body);

        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    } catch (Throwable $e) {
        $message->delivery_info['channel']->basic_nack($message->delivery_info['delivery_tag'], false, false);

        $logger->warning(
            'Unable to sent email: ' . $message->body . PHP_EOL
            . ' Error: ' . $e->getMessage() . $e->getTraceAsString());
    }
};

$channel->basic_consume($queueName, '', false, false, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}
