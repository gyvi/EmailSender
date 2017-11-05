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
     *
     * @throws \EmailSender\ComposedEmail\Infrastructure\Service\SMTPException
     * @throws \Exception
     */
    public function send(ComposedEmail $composedEmail): void;
}
