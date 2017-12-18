<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Date;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class DateTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class DateTest extends TestCase
{
    /**
     * Test getYear method.
     */
    public function testGetYear()
    {
        $yearValue = 2017;

        $date = new Date(
            (new Mockery($this))->getYearMock($yearValue),
            (new Mockery($this))->getMonthMock(1),
            (new Mockery($this))->getDayMock(1)
        );

        $this->assertEquals($yearValue, $date->getYear()->getValue());
    }

    /**
     * Test getMonth method.
     */
    public function testGetMonth()
    {
        $monthValue = 1;

        $date = new Date(
            (new Mockery($this))->getYearMock(2017),
            (new Mockery($this))->getMonthMock($monthValue),
            (new Mockery($this))->getDayMock(1)
        );

        $this->assertEquals($monthValue, $date->getMonth()->getValue());
    }

    /**
     * Test getDay method.
     */
    public function testGetDay()
    {
        $dayValue = 1;

        $date = new Date(
            (new Mockery($this))->getYearMock(2017),
            (new Mockery($this))->getMonthMock(1),
            (new Mockery($this))->getDayMock($dayValue)
        );

        $this->assertEquals($dayValue, $date->getDay()->getValue());
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $date = new Date(
            (new Mockery($this))->getYearMock(2017),
            (new Mockery($this))->getMonthMock(1),
            (new Mockery($this))->getDayMock(1)
        );

        $this->assertEquals('2017-01-01', $date->getValue());
    }

    /**
     * Test __toString method.
     */
    public function testToString()
    {
        $date = new Date(
            (new Mockery($this))->getYearMock(2017),
            (new Mockery($this))->getMonthMock(11),
            (new Mockery($this))->getDayMock(11)
        );

        $this->assertEquals('2017-11-11', (string)$date);
    }

    /**
     * Test __construct with invalid date.
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage Invalid date!
     */
    public function testConstructWithInvalidDate()
    {
        new Date(
            (new Mockery($this))->getYearMock(2017),
            (new Mockery($this))->getMonthMock(2),
            (new Mockery($this))->getDayMock(31)
        );
    }
}
