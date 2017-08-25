<?php

namespace EmailSender\MessageLog\Application\Route;

use EmailSender\Core\Route\RouteAbstract;
use EmailSender\Core\Route\RouteInterface;
use EmailSender\MessageLog\Application\Controller\MessageLogController;

/**
 * Class Route
 *
 * @package EmailSender\MessageLog
 */
class Route extends RouteAbstract implements RouteInterface
{
    /**
     * Init MessageLog routes.
     */
    public function init(): void
    {
        $this->application->post('/list', MessageLogController::class . ':listMessageLogs');
    }
}
