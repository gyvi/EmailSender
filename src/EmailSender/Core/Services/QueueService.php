<?php

namespace EmailSender\Core\Services;

use Psr\Container\ContainerInterface;
use Closure;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class QueueService
 *
 * @package EmailSender\Core
 */
class QueueService implements ServiceInterface
{
    /**
     * @return \Closure
     */
    public function getService(): Closure
    {
        return function (ContainerInterface $container): Closure {
            return function () use (&$container): AMQPStreamConnection {
                $settings = $container->get('settings')[ServiceList::QUEUE];

                return new AMQPStreamConnection(
                    $settings['host'],
                    $settings['port'],
                    $settings['username'],
                    $settings['password']
                );
            };
        };
    }
}
