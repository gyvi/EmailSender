<?php

namespace EmailSender\Core\Services;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Closure;
use Psr\Log\LoggerInterface;

/**
 * Class MonologService
 *
 * @package EmailSender\Core
 */
class LoggerService implements ServiceInterface
{
    /**
     * Get the Monolog Logger.
     */
    public function getService(): Closure
    {
        return function (): LoggerInterface {
            $log = new Logger('EmailSenderLog');
            $log->pushHandler(new StreamHandler(__DIR__ . '/../../../../log/application.log', Logger::NOTICE));

            return $log;
        };
    }
}