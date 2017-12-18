<?php

namespace Test\Helper\EmailSender\Mockery;

use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Date;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Day;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Hour;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Minute;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Month;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Second;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Time;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Year;

/**
 * Trait DateTimeMock
 *
 * @package Test\Helper
 */
trait DateTimeMock
{
    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getDateTimeMock(): DateTime
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $dateTimeMock = $testCase->getMockBuilder(DateTime::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $dateTimeMock;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Date|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getDateMock(): Date
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $dateMock = $testCase->getMockBuilder(Date::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $dateMock;
    }

    /**
     * @param int $value
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Day|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getDayMock(int $value): Day
    {
        return $this->getValueObjectMock(Day::class, $value);
    }

    /**
     * @param int $value
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Hour|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getHourMock(int $value): Hour
    {
        return $this->getValueObjectMock(Hour::class, $value);
    }

    /**
     * @param int $value
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Minute|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getMinuteMock(int $value): Minute
    {
        return $this->getValueObjectMock(Minute::class, $value);
    }

    /**
     * @param int $value
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Month|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getMonthMock(int $value): Month
    {
        return $this->getValueObjectMock(Month::class, $value);
    }

    /**
     * @param int $value
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Second|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getSecondMock(int $value): Second
    {
        return $this->getValueObjectMock(Second::class, $value);
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Time|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getTimeMock(): Time
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $timeMock = $testCase->getMockBuilder(Time::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $timeMock;
    }

    /**
     * @param int $value
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Year|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getYearMock(int $value): Year
    {
        return $this->getValueObjectMock(Year::class, $value);
    }
}
