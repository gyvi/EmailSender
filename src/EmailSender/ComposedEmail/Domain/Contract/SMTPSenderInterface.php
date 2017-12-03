<?php

namespace EmailSender\ComposedEmail\Domain\Contract;

use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;

/**
 * Interface SMTPSenderInterface
 *
 * @package EmailSender\EmailQueue\Domain\Contract
 */
interface SMTPSenderInterface
{
    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     */
    public function send(ComposedEmail $composedEmail): void;
}
