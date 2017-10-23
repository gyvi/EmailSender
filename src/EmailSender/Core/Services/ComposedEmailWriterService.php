<?php

namespace EmailSender\Core\Services;

use Closure;
use Psr\Container\ContainerInterface;
use PDO;

/**
 * Class ComposedEmailWriterService
 *
 * @package EmailSender\Core
 */
class ComposedEmailWriterService implements ServiceInterface
{
    /**
     * @return \Closure
     */
    public function getService(): Closure
    {
        return function (ContainerInterface $container): Closure {
            return function () use ($container): PDO {
                $settings = $container->get('settings')[ServiceList::COMPOSED_EMAIL_WRITER];

                return new PDO($settings['dsn'], $settings['username'], $settings['password'], $settings['options']);
            };
        };
    }
}
