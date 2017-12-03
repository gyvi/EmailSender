<?php

namespace EmailSender\Core\Services;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
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
            $log->pushHandler(new ErrorLogHandler());

            return $log;
        };
    }
}
