<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\Numeric;

/**
 * Class UnsignedInteger
 *
 * @package EmailSender\Core\Scalar
 */
class UnsignedInteger extends SignedInteger
{
    /**
     * Lower limit of the UnsignedInteger.
     */
    const LIMIT_LOWER = 0;

    /**
     * Upper limit of the UnsignedInteger.
     */
    const LIMIT_UPPER = PHP_INT_MAX;
}
