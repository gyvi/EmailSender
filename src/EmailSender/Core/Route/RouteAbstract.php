<?php

namespace EmailSender\Core\Route;

use Slim\App;

/**
 * Class RouteAbstract
 *
 * @package EmailSender\Core
 */
abstract class RouteAbstract
{
    /**
     * @var \Slim\App
     */
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
}
