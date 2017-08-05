<?php

namespace EmailSender\Core\Services;

use Closure;
use Psr\Container\ContainerInterface;
use PDO;

/**
 * Class MessageStoreReaderService
 *
 * @package EmailSender\Core
 */
class MessageStoreReaderService implements ServiceInterface
{
    /**
     * @return \Closure
     */
    public function getService(): Closure
    {
        return function (ContainerInterface $container): Closure {
            return function () use ($container): PDO {
                $settings = $container->get(ServiceList::MESSAGE_STORE_READER);

                return new PDO($settings['dsn'], $settings['user'], $settings['password'], $settings['options']);
            };
        };
    }
}
