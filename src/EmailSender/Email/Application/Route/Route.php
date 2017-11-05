<?php

namespace EmailSender\Email\Application\Route;

use EmailSender\Core\Route\RouteAbstract;
use EmailSender\Core\Route\RouteInterface;
use EmailSender\Email\Application\Controller\EmailController;

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
        $this->application->post('/api/v1/emails', EmailController::class . ':add');
    }
}
