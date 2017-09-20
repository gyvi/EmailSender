<?php

namespace EmailSender\Core\Route;

/**
 * Class Routing
 *
 * @package EmailSender\Core
 */
class Routing
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * @param \EmailSender\Core\Route\RouteInterface $route
     */
    public function add(RouteInterface $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * Initialize all routes.
     */
    public function init(): void
    {
        /** @var \EmailSender\Core\Route\RouteInterface $route */
        foreach ($this->routes as $route) {
            $route->init();
        }
    }
}
