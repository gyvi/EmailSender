<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

/**
 * Class Month
 *
 * @package EmailSender\Core\Scalar
 */
class Month extends UnsignedInteger
{
    /**
     * Lower limit.
     */
    const LIMIT_LOWER = 1;

    /**
     * Upper limit.
     */
    const LIMIT_UPPER = 12;
}
