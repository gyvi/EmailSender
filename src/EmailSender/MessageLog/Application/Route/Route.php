<?php

namespace EmailSender\MessageLog\Application\Route;

use EmailSender\Core\Route\RouteAbstract;
use EmailSender\Core\Route\RouteInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;

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
        $this->application->get('/', function (RequestInterface $request, ResponseInterface $response) {
            $response->getBody()->write('MessageLog');

            return $response;
        });
    }
}
