<?php

declare(strict_types=1);

require '../../vendor/autoload.php';

$settings  = require_once '../EmailSender/Core/Framework/settings.php';
$container = new \Slim\Container($settings);
$app       = (new \EmailSender\Core\Framework\Bootstrap($container))->init();

$app->run();
