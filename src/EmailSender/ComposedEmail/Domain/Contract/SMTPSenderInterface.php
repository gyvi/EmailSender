<?php

namespace EmailSender\ComposedEmail\Domain\Contract;

use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\Core\ValueObject\EmailStatus;

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
     * @return \EmailSender\Core\ValueObject\EmailStatus
     */
    public function send(ComposedEmail $composedEmail): EmailStatus;
}
