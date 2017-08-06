<?php

namespace EmailSender\Core\Services;

use Closure;
use Psr\Container\ContainerInterface;
use PDO;

/**
 * Class MessageStoreWriterService
 *
 * @package EmailSender\Core
 */
class MessageStoreWriterService implements ServiceInterface
{
    /**
     * @return \Closure
     */
    public function getService(): Closure
    {
        return function (ContainerInterface $container): Closure {
            return function () use (&$container): PDO {
                $settings = $container->get('settings')[ServiceList::MESSAGE_STORE_WRITER];

                return new PDO($settings['dsn'], $settings['username'], $settings['password'], $settings['options']);
            };
        };
    }
}
