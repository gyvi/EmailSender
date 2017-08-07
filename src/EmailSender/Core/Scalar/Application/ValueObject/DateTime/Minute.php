<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use DateTime;

/**
 * Class Minute
 *
 * @package EmailSender\Core\Scalar
 */
class Minute extends UnsignedInteger
{
    const LIMIT_LOWER = 0;

    const LIMIT_UPPER = 59;

    /**
     * @param \DateTime $dateTime
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Minute
     */
    public static function buildFromDateTime(DateTime $dateTime): Minute
    {
        return new static(
            intval($dateTime->format('i'))
        );
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Minute
     */
    public static function buildFromDefault(): Minute
    {
        return new static(
            intval((new DateTime('now'))->format('i'))
        );
    }
}
