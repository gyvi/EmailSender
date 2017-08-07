<?php

namespace EmailSender\Core\Services;

use Closure;
use EmailSender\MessageStore\Domain\Composer\EmailComposer;
use PHPMailer;

/**
 * Class EmailBuilderService
 *
 * @package EmailSender\Core
 */
class EmailComposerService implements ServiceInterface
{
    /**
     * Get the EmailBuilder.
     *
     * @return \Closure
     */
    public function getService(): Closure
    {
        return function () {
            $phpMailer = new PHPMailer();

            $emailComposer = new EmailComposer($phpMailer);

            return $emailComposer;
        };
    }
}