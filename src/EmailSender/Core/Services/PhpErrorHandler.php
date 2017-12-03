<?php

namespace EmailSender\Core\Services;

use Closure;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * Class PhpErrorHandler
 *
 * @package EmailSender\Core
 */
class PhpErrorHandler implements ServiceInterface
{
    /**
     * Return with the PhpErrorHandler
     *
     * @return \Closure
     */
    public function getService(): Closure
    {
        return function (ContainerInterface $container) {
            return function (
                ServerRequestInterface $request,
                ResponseInterface $response,
                Throwable $exception
            ) use ($container) {
                /** @var \Monolog\Logger $logger */
                $logger = $container->get(ServiceList::LOGGER);
                $logger->alert($exception->getMessage(), $exception->getTrace());

                /** @var \Slim\Http\Response $response */
                return $response->withJson(
                    [
                        'status' => 'An unexpected error occurred.',
                        'statusMessage' => $exception->getMessage()
                    ],
                    500
                );
            };
        };
    }
}
