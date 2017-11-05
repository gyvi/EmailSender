<?php

namespace EmailSender\ComposedEmail\Domain\Contract;

use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

/**
 * Interface ComposedEmailRepositoryWriterInterface
 *
 * @package EmailSender\ComposedEmail
 */
interface ComposedEmailRepositoryWriterInterface
{
    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger
     *
     * @throws \Error
     */
    public function add(ComposedEmail $composedEmail): UnsignedInteger;
}
