<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use DateTime;

/**
 * Class Hour
 *
 * @package EmailSender\Core\Scalar
 */
class Hour extends UnsignedInteger
{
    const LIMIT_LOWER = 0;

    const LIMIT_UPPER = 23;

    /**
     * @param \DateTime $dateTime
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Hour
     */
    public static function buildFromDateTime(DateTime $dateTime): Hour
    {
        return new static(
            intval($dateTime->format('G'))
        );
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Hour
     */
    public static function buildFromDefault(): Hour
    {
        return new static(
            intval((new DateTime('now'))->format('G'))
        );
    }
}
