<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

/**
 * Class Minute
 *
 * @package EmailSender\Core\Scalar
 */
class Minute extends UnsignedInteger
{
    /**
     * Lower limit.
     */
    const LIMIT_LOWER = 0;

    /**
     * Upper limit.
     */
    const LIMIT_UPPER = 59;
}
