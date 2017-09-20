<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;

/**
 * Class Day
 *
 * @package EmailSender\Core\Scalar
 */
class Day extends UnsignedInteger
{
    /**
     * Lower limit.
     *
     * @var int
     */
    const LIMIT_LOWER = 1;

    /**
     * Upper limit.
     *
     * @var int
     */
    const LIMIT_UPPER = 31;
}
