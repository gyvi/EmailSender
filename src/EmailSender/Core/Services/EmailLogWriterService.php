<?php

namespace EmailSender\Core\Services;

use Closure;
use Psr\Container\ContainerInterface;
use PDO;

/**
 * Class EmailLogWriterService
 *
 * @package EmailSender\Core
 */
class EmailLogWriterService implements ServiceInterface
{
    /**
     * @return \Closure
     */
    public function getService(): Closure
    {
        return function (ContainerInterface $container): Closure {
            return function () use ($container): PDO {
                $settings = $container->get('settings')[ServiceList::EMAIL_LOG_WRITER];

                return new PDO($settings['dsn'], $settings['username'], $settings['password'], $settings['options']);
            };
        };
    }
}
