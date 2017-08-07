<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use DateTime;

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

    /**
     * @param \DateTime $dateTime
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Second
     */
    public static function buildFromDateTime(\DateTime $dateTime): Second
    {
        return new static(
            intval($dateTime->format('s'))
        );
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Second
     */
    public static function buildFromDefault(): Second
    {
        return new static(
            intval((new DateTime('now'))->format('s'))
        );
    }
}
