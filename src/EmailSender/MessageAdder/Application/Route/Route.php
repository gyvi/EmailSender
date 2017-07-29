<?php

namespace EmailSender\MessageAdder\Application\Route;

use EmailSender\Core\Route\RouteAbstract;
use EmailSender\Core\Route\RouteInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Route
 *
 * @package EmailSender\MessageAdder
 */
class Route extends RouteAbstract implements RouteInterface
{
    /**
     * Init MessageAdder routes.
     */
    public function init(): void
    {
        $this->application->post('/add', function (RequestInterface $request, ResponseInterface $response) {
            $response->getBody()->write('MessageAdder');

            return $response;
        });
    }
}
