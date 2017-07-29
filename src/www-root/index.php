<?php

declare(strict_types=1);

require '../../vendor/autoload.php';

$settings  = require_once '../EmailSender/Core/Framework/settings.php';
$container = new Slim\Container($settings);
$app       = new Slim\App($container);
$routing   = new EmailSender\Core\Route\Routing();

$routing->add(new \EmailSender\MessageLogViewer\Application\Route\Route($app));
$routing->add(new \EmailSender\MessageAdder\Application\Route\Route($app));

$routing->init();

$app->run();
