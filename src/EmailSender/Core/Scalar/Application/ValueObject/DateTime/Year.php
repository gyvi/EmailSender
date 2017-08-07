<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use DateTime;

/**
 * Class Year
 *
 * @package EmailSender\Core\Scalar
 */
class Year extends UnsignedInteger
{
    /**
     * @param \DateTime $dateTime
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Year
     */
    public static function buildFromDateTime(DateTime $dateTime): Year
    {
        return new static(
            intval($dateTime->format('Y'))
        );
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Year
     */
    public static function buildFromDefault(): Year
    {
        return new static(
            intval((new DateTime('now'))->format('Y'))
        );
    }
}
