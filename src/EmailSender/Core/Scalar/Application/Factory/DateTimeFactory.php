<?php

namespace EmailSender\Core\Scalar\Application\Factory;

use EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Date;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Year;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Month;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Day;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Time;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Hour;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Minute;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Second;

/**
 * Class DateTimeFactory
 *
 * @package EmailSender\Core
 */
class DateTimeFactory
{
    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     * @param int $second
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime
     */
    public function createFromNative(int $year, int $month, int $day, int $hour, int $minute, int $second): DateTime
    {
        return new DateTime(
            new Date(
                new Year($year),
                new Month($month),
                new Day($day)
            ),
            new Time(
                new Hour($hour),
                new Minute($minute),
                new Second($second)
            )
        );
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime
     */
    public function createFromDateTime(\DateTime $dateTime): DateTime
    {
        return new DateTime(
            new Date(
                new Year((int)$dateTime->format('Y')),
                new Month((int)$dateTime->format('n')),
                new Day((int)$dateTime->format('j'))
            ),
            new Time(
                new Hour((int)$dateTime->format('G')),
                new Minute((int)$dateTime->format('i')),
                new Second((int)$dateTime->format('s'))
            )
        );
    }
}
