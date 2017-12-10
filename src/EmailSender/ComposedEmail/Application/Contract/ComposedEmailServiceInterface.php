<?php

namespace EmailSender\ComposedEmail\Application\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;

/**
 * Interface ComposedEmailServiceInterface
 *
 * @package EmailSender\ComposedEmail
 */
interface ComposedEmailServiceInterface
{
    /**
     * @param \EmailSender\Email\Domain\Aggregate\Email $email
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     */
    public function add(Email $email): ComposedEmail;

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     */
    public function get(UnsignedInteger $composedEmailId): ComposedEmail;

    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @return \EmailSender\Core\ValueObject\EmailStatus
     *
     * @throws \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     */
    public function send(ComposedEmail $composedEmail): EmailStatus;

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     *
     * @return \EmailSender\Core\ValueObject\EmailStatus
     *
     * @throws \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     */
    public function sendById(UnsignedInteger $composedEmailId): EmailStatus;
}
