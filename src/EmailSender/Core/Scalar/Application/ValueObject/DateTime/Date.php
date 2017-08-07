<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\Contract\ValueObjectInterface;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use DateTime;

/**
 * Class Date
 *
 * @package EmailSender\Core\Scalar
 */
class Date implements ValueObjectInterface
{
    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Year
     */
    private $year;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Month
     */
    private $month;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Day
     */
    private $day;

    /**
     * Date constructor.
     *
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Year  $year
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Month $month
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Day   $day
     */
    public function __construct(Year $year, Month $month, Day $day)
    {
        $this->validate($year, $month, $day);

        $this->year  = $year;
        $this->month = $month;
        $this->day   = $day;
    }

    /**
     * @param $year
     * @param $month
     * @param $day
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Date
     */
    public static function buildFromNative($year, $month, $day): Date
    {
        return new static(
            new Year($year),
            new Month($month),
            new Day($day)
        );
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Date
     */
    public static function buildFromDateTime(DateTime $dateTime): Date
    {
        return new static(
            Year::buildFromDateTime($dateTime),
            Month::buildFromDateTime($dateTime),
            Day::buildFromDateTime($dateTime)
        );
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Date
     */
     public static function buildFromDefault(): Date
    {
        return new static(
            Year::buildFromDefault(),
            Month::buildFromDefault(),
            Day::buildFromDefault()
        );
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Year
     */
    public function getYear(): Year
    {
        return clone $this->year;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Month
     */
    public function getMonth(): Month
    {
        return $this->month;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Day
     */
    public function getDay(): Day
    {
        return clone $this->day;
    }

    /**
     * Returns string representation of date in Y-m-d format.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->__toString();
    }

    /**
     * Returns string representation of date in Y-m-d format.
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            '%d-%02d-%02d',
            $this->year->getValue(),
            $this->month->getValue(),
            $this->day->getValue()
        );
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Year  $year
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Month $month
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Day   $day
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    private function validate(Year $year, Month $month, Day $day): void
    {
        $date = sprintf('%d-%d-%d', $year->getValue(), $month->getValue(), $day->getValue());
        DateTime::createFromFormat('Y-n-j', $date);

        $nativeDateErrors = DateTime::getLastErrors();
        if ($nativeDateErrors['warning_count'] > 0 || $nativeDateErrors['error_count'] > 0) {
            throw new ValueObjectException('Invalid date!');
        }
    }
}
