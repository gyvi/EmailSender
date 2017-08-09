<?php

declare(strict_types=1);

require __DIR__ . '/../../../../../vendor/autoload.php';

use EmailSender\MessageQueue\Application\Service\MessageQueueService;
use EmailSender\Core\Services\ServiceList;
use EmailSender\Core\Framework\BootstrapCli;
use Slim\Container;

$settings  = require_once __DIR__ . '/../../../../EmailSender/Core/Framework/settings.php';
$container = new Container($settings);
(new BootstrapCli($container))->init();

/** @var \Psr\Log\LoggerInterface $logger */
$logger        = $container->get(ServiceList::LOGGER);
$queueSettings = $container->get('settings')[ServiceList::QUEUE];

/** @var \AMQPConnection $connection */
$connection = $container->get(ServiceList::QUEUE)();

/** @var \AMQPChannel $channel */
$channel = $connection->channel();

/** @var array $queue */
$queue = $queueSettings['queue'];

$callback = function ($message) use (&$container, $queueSettings, $logger) {
    try {
        $messageQueueService = new MessageQueueService(
            $logger,
            $container->get(ServiceList::QUEUE),
            $queueSettings,
            $container->get(ServiceList::MESSAGE_STORE_READER),
            $container->get(ServiceList::MESSAGE_STORE_WRITER),
            $container->get(ServiceList::MESSAGE_LOG_READER),
            $container->get(ServiceList::MESSAGE_LOG_WRITER)
        );

        $messageQueueService->sendMessageFromQueue($message->body);

        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    } catch (Throwable $e) {
        $message->delivery_info['channel']->basic_nack($message->delivery_info['delivery_tag'], false, false);

        $logger->warning('Unable to sent email: ' . $message->body);
    }

    unset($messageQueueService);
}

$channel->basic_consume($queue, '', false, false, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}
