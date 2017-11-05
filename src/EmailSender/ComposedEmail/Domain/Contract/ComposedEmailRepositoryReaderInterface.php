<?php

namespace EmailSender\ComposedEmail\Domain\Contract;

use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

/**
 * Class ComposedEmailRepositoryReaderInterface
 *
 * @package EmailSender\ComposedEmail
 */
interface ComposedEmailRepositoryReaderInterface
{
    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \Error
     * @throws \InvalidArgumentException
     */
    public function get(UnsignedInteger $composedEmailId): ComposedEmail;
}
