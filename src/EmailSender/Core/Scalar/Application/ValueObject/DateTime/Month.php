<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use DateTime;

/**
 * Class Month
 *
 * @package EmailSender\Core\Scalar
 */
class Month extends UnsignedInteger
{
    const LIMIT_LOWER = 1;

    const LIMIT_UPPER = 12;

    /**
     * @param \DateTime $dateTime
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Month
     */
    public static function buildFromDateTime(DateTime $dateTime): Month
    {
        return new static(
            intval($dateTime->format('n'))
        );
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Month
     */
    public static function buildFromDefault(): Month
    {
        return new static(
            intval((new DateTime('now'))->format('n'))
        );
    }
}
