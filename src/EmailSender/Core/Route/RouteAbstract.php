<?php

namespace EmailSender\Core\Route;

use Slim\App;

class RouteAbstract implements RouteInterface
{
    protected $application;

    /**
     * RouteAbstract constructor.
     *
     * @param \Slim\App $application
     */
    public function __construct(App $application)
    {
        $this->application = $application;
    }

    /**
     * Routes init method.
     */
    public function init(): void
    {
    }
}
