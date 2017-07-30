<?php

namespace EmailSender\MessageQueue\Application\Route;

use EmailSender\Core\Route\RouteAbstract;
use EmailSender\Core\Route\RouteInterface;
use EmailSender\MessageQueue\Application\Controller\MessageQueueController;

/**
 * Class Route
 *
 * @package EmailSender\MessageQueue
 */
class Route extends RouteAbstract implements RouteInterface
{
    /**
     * Init MessageQueue routes.
     */
    public function init(): void
    {
        $this->application->post('/add', MessageQueueController::class . ':addMessageToQueue');
    }
}
