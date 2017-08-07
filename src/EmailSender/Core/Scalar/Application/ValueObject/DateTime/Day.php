<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use DateTime;

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

    /**
     * @param \DateTime $dateTime
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Day
     */
    public static function buildFromDateTime(DateTime $dateTime): Day
    {
        return new static(
            intval($dateTime->format('j'))
        );
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Day
     */
    public static function buildFromDefault(): Day
    {
        return new static(
            intval((new DateTime('now'))->format('j'))
        );
    }
}
