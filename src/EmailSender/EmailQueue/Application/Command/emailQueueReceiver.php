<?php

declare(strict_types=1);

require __DIR__ . '/../../../../../vendor/autoload.php';

use EmailSender\EmailQueue\Application\Service\EmailQueueService;
use EmailSender\Core\Services\ServiceList;
use EmailSender\Core\Framework\BootstrapCli;
use Slim\Container;

$settings  = require __DIR__ . '/../../../../EmailSender/Core/Framework/settings.php';
$container = new Container($settings);

// Bootstrap init
(new BootstrapCli($container))->init();

/** @var \Psr\Log\LoggerInterface $logger */
$logger        = $container->get(ServiceList::LOGGER);
$queueSettings = $container->get('settings')[ServiceList::QUEUE];
$view          = $container->get('view');

/** @var \PhpAmqpLib\Connection\AMQPStreamConnection $connection */
$connection = $container->get(ServiceList::QUEUE)();

/** @var \PhpAmqpLib\Channel\AMQPChannel $channel */
$channel = $connection->channel();

/** @var array $queueName */
$queueName = $queueSettings['queue'];

$callback = function ($message) use ($container, $queueSettings, $logger, $view) {
    /** @var \PhpAmqpLib\Message\AMQPMessage $message */

    /** @var \PhpAmqpLib\Channel\AMQPChannel $deliveryChannel */
    $deliveryChannel = $message->delivery_info['channel'];
    try {
        $emailQueueService = new EmailQueueService(
            $view,
            $logger,
            $container->get(ServiceList::QUEUE),
            $queueSettings,
            $container->get(ServiceList::COMPOSED_EMAIL_READER),
            $container->get(ServiceList::COMPOSED_EMAIL_WRITER),
            $container->get(ServiceList::EMAIL_LOG_READER),
            $container->get(ServiceList::EMAIL_LOG_WRITER),
            $container->get(ServiceList::SMTP)
        );

        $emailQueueService->sendEmailFromQueue($message->body);

        $logger->notice('Sent email: ' . $message->body);

        $deliveryChannel->basic_ack($message->delivery_info['delivery_tag']);
    } catch (Throwable $e) {
        $deliveryChannel->basic_nack($message->delivery_info['delivery_tag']);

        $logger->warning(
            'Unable to sent email: ' . $message->body . PHP_EOL . ' Error: ' .
            $e->getMessage() . $e->getTraceAsString()
        );
    }
};

$channel->basic_consume($queueName, '', false, false, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}
