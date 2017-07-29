<?php

namespace EmailSender\MessageAdder\Application\Route;

use EmailSender\Core\Route\RouteAbstract;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Route
 *
 * @package EmailSender\MessageAdder\Application\Route
 */
class Route extends RouteAbstract
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
