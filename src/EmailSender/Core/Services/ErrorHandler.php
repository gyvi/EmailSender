<?php

namespace EmailSender\Core\Services;

use Closure;
use InvalidArgumentException;
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
     * Return with the Errorhandler
     *
     * @return \Closure
     */
    public function getService(): Closure
    {
        return function (ContainerInterface $container) {
            return function (
                ServerRequestInterface $request,
                ResponseInterface $response,
                Throwable $throwable
            ) use ($container) {
                $httpResponseCode = 500;

                /** @var \Monolog\Logger $logger */
                $logger = $container->get(ServiceList::LOGGER);
                $logger->notice($throwable->getMessage(), $throwable->getTrace());

                $errorMessage = $throwable->getMessage();

                if ($throwable instanceof InvalidArgumentException) {
                    $httpResponseCode = 400;
                }

                while ($throwable = $throwable->getPrevious()) {
                    $errorMessage .= ' - ' . $throwable->getMessage();
                }

                /** @var \Slim\Http\Response $response */
                return $response
                    ->withJson(
                        [
                            'status' => -1,
                            'statusMessage' => $errorMessage
                        ],
                        $httpResponseCode
                    );
            };
        };
    }
}
