<?php

declare(strict_types=1);

require '../../vendor/autoload.php';

$settings  = require_once '../EmailSender/Core/Framework/settings.php';
$container = new Slim\Container($settings);

$serviceProvider = new \EmailSender\Core\Services\ServiceProvider($container);

$serviceProvider->addService(
    \EmailSender\Core\Services\ServiceList::LOGGER, new \EmailSender\Core\Services\LoggerService()
);

$serviceProvider->addService(
    \EmailSender\Core\Services\ServiceList::ERROR_HANDLER, new \EmailSender\Core\Services\ErrorHandler()
);

$serviceProvider->addService(
    \EmailSender\Core\Services\ServiceList::PHP_ERROR_HANDLER, new \EmailSender\Core\Services\PhpErrorHandler()
);

$serviceProvider->init();

$app       = new Slim\App($container);
$routing   = new EmailSender\Core\Route\Routing();

$routing->add(new \EmailSender\MessageLog\Application\Route\Route($app));
$routing->add(new \EmailSender\MessageQueue\Application\Route\Route($app));

$routing->init();

$app->run();
