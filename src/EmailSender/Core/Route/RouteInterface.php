<?php

namespace EmailSender\Core\Route;

/**
 * Interface RoutingInterface
 *
 * @package EmailSender\Core\Route
 */
interface RouteInterface
{
    /**
     * Init the routes of a feature.
     */
    public function init(): void;
}
