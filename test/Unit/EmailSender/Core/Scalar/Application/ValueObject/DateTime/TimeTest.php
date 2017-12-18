<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Time;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class TimeTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class TimeTest extends TestCase
{
    /**
     * Test getHour method.
     */
    public function testGetHour()
    {
        $hourValue = 1;

        $time = new Time(
            (new Mockery($this))->getHourMock($hourValue),
            (new Mockery($this))->getMinuteMock(1),
            (new Mockery($this))->getSecondMock(1)
        );

        $this->assertEquals($hourValue, $time->getHour()->getValue());
    }

    /**
     * Test getMinute method.
     */
    public function testGetMinute()
    {
        $minuteValue = 1;

        $time = new Time(
            (new Mockery($this))->getHourMock(1),
            (new Mockery($this))->getMinuteMock($minuteValue),
            (new Mockery($this))->getSecondMock(1)
        );

        $this->assertEquals($minuteValue, $time->getMinute()->getValue());
    }

    /**
     * Test getSecond method.
     */
    public function testGetSecond()
    {
        $secondValue = 1;

        $time = new Time(
            (new Mockery($this))->getHourMock(1),
            (new Mockery($this))->getMinuteMock(1),
            (new Mockery($this))->getSecondMock($secondValue)
        );

        $this->assertEquals($secondValue, $time->getSecond()->getValue());
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $time = new Time(
            (new Mockery($this))->getHourMock(1),
            (new Mockery($this))->getMinuteMock(1),
            (new Mockery($this))->getSecondMock(1)
        );

        $this->assertEquals('01:01:01', $time->getValue());
    }

    /**
     * Test __toString method.
     */
    public function testToString()
    {
        $time = new Time(
            (new Mockery($this))->getHourMock(1),
            (new Mockery($this))->getMinuteMock(1),
            (new Mockery($this))->getSecondMock(1)
        );

        $this->assertEquals('01:01:01', (string)$time);
    }
}
