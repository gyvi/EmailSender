<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$settings  = require dirname(__DIR__) . '/config/settings.php';
$container = new \Slim\Container($settings);
$app       = (new \EmailSender\Core\Framework\Bootstrap($container))->init();

$app->run();
