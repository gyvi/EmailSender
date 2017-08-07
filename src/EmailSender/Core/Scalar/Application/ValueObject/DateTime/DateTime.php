<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\Contract\ValueObjectInterface;

/**
 * Class DateTime
 *
 * @package EmailSender\Core\Scalar
 */
class DateTime implements ValueObjectInterface
{
    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Date
     */
    private $date;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Time
     */
    private $time;

    /**
     * DateTime constructor.
     *
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Date $date
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Time $time
     */
    public function __construct(Date $date, Time $time)
    {
        $this->date = $date;
        $this->time = $time;
    }

    /**
     * @param $year
     * @param $month
     * @param $day
     * @param $hour
     * @param $minute
     * @param $second
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime
     */
    public static function buildFromNative($year, $month, $day, $hour, $minute, $second): DateTime
    {
        return new static(
            Date::buildFromNative($year, $month, $day),
            Time::buildFromNative($hour, $minute, $second)
        );
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime
     */
    public static function buildFromDateTime(\DateTime $dateTime): DateTime
    {
        return new static(
            Date::buildFromDateTime($dateTime),
            Time::buildFromDateTime($dateTime)
        );
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime
     */
    public static function buildFromDefault(): DateTime
    {
        return new static(
            Date::buildFromDefault(),
            Time::buildFromDefault()
        );
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Date
     */
    public function getDate(): Date
    {
        return clone $this->date;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Time
     */
    public function getTime(): Time
    {
        return $this->time;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime(): \DateTime
    {
        return \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            sprintf('%s %s', $this->date->__toString(), $this->time->getValue())
        );
    }

    /**
     * @return int
     */
    public function getUnixTimestamp(): int
    {
        return $this->getDateTime()->getTimestamp();
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->__toString();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            '%s %s',
            $this->date->__toString(),
            $this->time->__toString()
        );
    }
}
