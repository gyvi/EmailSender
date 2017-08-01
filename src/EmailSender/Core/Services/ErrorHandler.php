<?php

namespace EmailSender\Core\Services;

use Closure;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * Class ErrorHandler
 *
 * @package EmailSender\Core
 */
class ErrorHandler implements ServiceInterface
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
                Throwable$exception
            ) use ($container) {
                /** @var \Monolog\Logger $logger */
                $logger = $container->get(ServiceList::LOGGER);
                $logger->notice($exception->getMessage(), $exception->getTrace());

                $errorMessage = $exception->getMessage();
                while ($exception = $exception->getPrevious()) {
                    $errorMessage .= ' - ' . $exception->getMessage();
                }

                return $container['response']
                    ->withStatus(400)
                    ->withHeader('Content-Type', 'application/json')
                    ->write(
                        json_encode([
                            'status' => -1,
                            'statusMessage' => $errorMessage,
                        ])
                    );
            };
        };
    }
}