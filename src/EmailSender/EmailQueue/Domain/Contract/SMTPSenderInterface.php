<?php

namespace EmailSender\EmailQueue\Domain\Contract;

use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;

/**
 * Interface SMTPSenderInterface
 *
 * @package EmailSender\EmailQueue\Domain\Contract
 */
interface SMTPSenderInterface
{
    /**
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog           $emailLog
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @throws \EmailSender\EmailQueue\Infrastructure\Service\SMTPException
     * @throws \Exception
     */
    public function send(EmailLog $emailLog, ComposedEmail $composedEmail): void;
}
