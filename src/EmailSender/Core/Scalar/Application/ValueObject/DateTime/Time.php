<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\Contract\ValueObjectInterface;

/**
 * Class Time
 *
 * @package EmailSender\Core\Scalar
 */
class Time implements ValueObjectInterface
{
    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Hour
     */
    private $hour;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Minute
     */
    private $minute;

    /**
     * @var \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Second
     */
    private $second;

    /**
     * Time constructor.
     *
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Hour   $hour
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Minute $minute
     * @param \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Second $second
     */
    public function __construct(Hour $hour, Minute $minute, Second $second)
    {
        $this->hour   = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Hour
     */
    public function getHour(): Hour
    {
        return clone $this->hour;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Minute
     */
    public function getMinute(): Minute
    {
        return $this->minute;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Second
     */
    public function getSecond(): Second
    {
        return $this->second;
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
            '%02d:%02d:%02d',
            $this->hour->getValue(),
            $this->minute->getValue(),
            $this->second->getValue()
        );
    }
}
