<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

/**
 * Class Hour
 *
 * @package EmailSender\Core\Scalar
 */
class Hour extends UnsignedInteger
{
    /**
     * Hour's lower limit.
     */
    const LIMIT_LOWER = 0;

    /**
     * Hour's upper limit.
     */
    const LIMIT_UPPER = 23;
}
