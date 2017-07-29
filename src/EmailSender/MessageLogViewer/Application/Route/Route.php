<?php

namespace EmailSender\MessageLogViewer\Application\Route;

use EmailSender\Core\Route\RouteAbstract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;

class Route extends RouteAbstract
{
    /**
     * Init MessageLogViewer routes.
     */
    public function init(): void
    {
        $this->application->get('/', function (RequestInterface $request, ResponseInterface $response) {
            $response->getBody()->write('MessageLogViewer');

            return $response;
        });
    }
}
