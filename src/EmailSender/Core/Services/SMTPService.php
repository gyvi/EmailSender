<?php

namespace EmailSender\Core\Services;

use Closure;
use EmailSender\MessageQueue\Infrastructure\Service\SMTPException;
use Interop\Container\ContainerInterface;
use SMTP;

/**
 * Class SmtpService
 *
 * @package EmailSender\Core
 */
class SMTPService implements ServiceInterface
{
    /**
     * @return \Closure
     */
    public function getService(): Closure
    {
        return function (ContainerInterface $container): Closure {
            return function () use ($container): SMTP {
                $settings = $container->get('settings')[ServiceList::SMTP];
                $SMTP     = new SMTP();

                $SMTP->connect($settings['host'], $settings['port']);

                if (!$SMTP->connected()) {
                    throw new SMTPException(
                        'Unable to connect to the SMTP server: ' . $settings['host'] . ':' . $settings['port']
                    );
                }

                $SMTP->hello();

                $SMTP->authenticate($settings['username'], $settings['password']);

                return $SMTP;
            };
        };
    }
}
