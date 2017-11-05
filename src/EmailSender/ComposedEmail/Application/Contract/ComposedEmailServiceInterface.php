<?php

namespace EmailSender\ComposedEmail\Application\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
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
     * @throws \Error
     * @throws \InvalidArgumentException
     * @throws \phpmailerException
     */
    public function add(Email $email): ComposedEmail;

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \Error
     * @throws \InvalidArgumentException
     */
    public function get(UnsignedInteger $composedEmailId): ComposedEmail;

    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     */
    public function send(ComposedEmail $composedEmail): void;

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     */
    public function sendById(UnsignedInteger $composedEmailId): void;
}
