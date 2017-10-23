<?php

namespace EmailSender\ComposedEmail\Domain\Contract;

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
     * @return array
     *
     * @throws \Error
     */
    public function get(UnsignedInteger $composedEmailId): array;
}
