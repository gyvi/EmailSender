<?php

namespace EmailSender\EmailQueue\Application\Route;

use EmailSender\Core\Route\RouteAbstract;
use EmailSender\Core\Route\RouteInterface;
use EmailSender\EmailQueue\Application\Controller\EmailQueueController;

/**
 * Class Route
 *
 * @package EmailSender\EmailQueue
 */
class Route extends RouteAbstract implements RouteInterface
{
    /**
     * Init EmailQueue routes.
     */
    public function init(): void
    {
        $this->application->post('/api/v1/emails', EmailQueueController::class . ':addEmailToQueue');
    }
}
