<?php

namespace EmailSender\Core\Services;

use Closure;
use EmailSender\MessageStore\Domain\Builder\EmailBuilderWithPHPMailer;
use PHPMailer;

/**
 * Class EmailBuilderService
 *
 * @package EmailSender\Core
 */
class EmailBuilderService implements ServiceInterface
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

            $emailBuilderWithPHPMailer = new EmailBuilderWithPHPMailer($phpMailer);

            return $emailBuilderWithPHPMailer;
        };
    }
}