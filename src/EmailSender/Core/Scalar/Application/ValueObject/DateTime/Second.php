<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

/**
 * Class Second
 *
 * @package EmailSender\Core\Scalar
 */
class Second extends UnsignedInteger
{
    /**
     * Lower limit.
     *
     * @var int
     */
    const LIMIT_LOWER = 0;

    /**
     * Upper limit.
     *
     * @var int
     */
    const LIMIT_UPPER = 59;
}
