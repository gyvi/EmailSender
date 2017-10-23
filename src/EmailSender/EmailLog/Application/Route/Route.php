<?php

namespace EmailSender\EmailLog\Application\Route;

use EmailSender\Core\Route\RouteAbstract;
use EmailSender\Core\Route\RouteInterface;
use EmailSender\EmailLog\Application\Controller\EmailLogController;

/**
 * Class Route
 *
 * @package EmailSender\EmailLog
 */
class Route extends RouteAbstract implements RouteInterface
{
    /**
     * Init EmailLog routes.
     */
    public function init(): void
    {
        $this->application->get('/api/v1/emails/logs', EmailLogController::class . ':list');

        $this->application->get('/emails/list', EmailLogController::class . ':lister');
    }
}
