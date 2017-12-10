<?php

declare(strict_types=1);

require dirname(__DIR__, 5) . '/vendor/autoload.php';

use EmailSender\Core\Services\ServiceList;
use EmailSender\Core\Framework\BootstrapCli;
use EmailSender\ComposedEmail\Application\Service\ComposedEmailService;
use EmailSender\EmailLog\Application\Service\EmailLogService;
use EmailSender\EmailQueue\Application\Catalog\EmailQueuePropertyNamesList;
use Slim\Container;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory;

$settings  = require dirname(__DIR__, 5) . '/config/settings.php';
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

$callback = function ($message) use ($container, $view, $logger) {
    /** @var \PhpAmqpLib\Message\AMQPMessage $message */

    /** @var \PhpAmqpLib\Channel\AMQPChannel $deliveryChannel */
    $deliveryChannel = $message->delivery_info['channel'];

    $emailQueueArray = json_decode($message->body, true);
    $emailQueue      = (new EmailQueueFactory())->createFromArray($emailQueueArray);
    $emailLogService = new EmailLogService(
        $view,
        $logger,
        $container->get(ServiceList::EMAIL_LOG_READER),
        $container->get(ServiceList::EMAIL_LOG_WRITER)
    );

    $composedEmailService = new ComposedEmailService(
        $logger,
        $container->get(ServiceList::COMPOSED_EMAIL_READER),
        $container->get(ServiceList::COMPOSED_EMAIL_WRITER),
        $container->get(ServiceList::SMTP)
    );

    try {
        $composedEmailService->sendById($emailQueue->getComposedEmailId());

        $emailLogService->setStatus(
            $emailQueue->getEmailLogId(),
            new EmailStatus(EmailStatusList::STATUS_SENT),
            ''
        );

        $deliveryChannel->basic_ack($message->delivery_info['delivery_tag']);
    } catch (Throwable $e) {
        $deliveryChannel->basic_nack($message->delivery_info['delivery_tag']);

        $logger->alert(
            'Unable to sent email: ' . $message->body . PHP_EOL . ' Error: ' .
            $e->getMessage() . $e->getTraceAsString()
        );

        $emailLogService->setStatus(
            new UnsignedInteger($emailQueue[EmailQueuePropertyNamesList::EMAIL_LOG_ID]),
            new EmailStatus(EmailStatusList::STATUS_ERROR),
            $e->getMessage()
        );
    }
};

$channel->basic_consume($queueName, '', false, false, false, false, $callback);

echo 'queueConsumer started.' . PHP_EOL;

while (count($channel->callbacks)) {
    $channel->wait();
}
