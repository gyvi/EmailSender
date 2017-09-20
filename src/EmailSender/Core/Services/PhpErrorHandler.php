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
     * Return with the PhpErrorhandler
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
                $logger->warning($exception->getMessage(), $exception->getTrace());

                return $response->withStatus(500)
                    ->withHeader('Content-Type', 'application/json')
                    ->write(
                        json_encode([
                            'status' => -1,
                            'statusMessage' => 'An unexpected error occurred.',
                        ])
                    );
            };
        };
    }
}
