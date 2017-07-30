<?php

namespace EmailSender\MessageLogViewer\Application\Route;

use EmailSender\Core\Route\RouteAbstract;
use EmailSender\Core\Route\RouteInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Class Route
 *
 * @package EmailSender\MessageLogViewer
 */
class Route extends RouteAbstract implements RouteInterface
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
