<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class DateTimeTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class DateTimeTest extends TestCase
{
    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Date|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getDate()
    {
        $date = (new Mockery($this))->getDateMock();
        $date->expects($this->once())
            ->method('__toString')
            ->willReturn('2017-01-01');

        return $date;
    }

    /**
     * @return \EmailSender\Core\Scalar\Application\ValueObject\DateTime\Time|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getTime()
    {
        $time = (new Mockery($this))->getTimeMock();
        $time->expects($this->once())
            ->method('__toString')
            ->willReturn('00:00:00');

        return $time;
    }

    /**
     * Test getDate method.
     */
    public function testGetDate()
    {
        $date = (new Mockery($this))->getDateMock();

        $dateTime = new DateTime(
            $date,
            (new Mockery($this))->getTimeMock()
        );

        $this->assertEquals($date, $dateTime->getDate());
    }

    /**
     * Test getTime method.
     */
    public function testGetTime()
    {
        $time = (new Mockery($this))->getTimeMock();

        $dateTime = new DateTime(
            (new Mockery($this))->getDateMock(),
            $time
        );

        $this->assertEquals($time, $dateTime->getTime());
    }

    /**
     * Test getDateTime method.
     */
    public function testGetDateTime()
    {
        $date = $this->getDate();
        $time = $this->getTime();

        $dateTime      = new DateTime($date, $time);
        $dateTimeClass = $dateTime->getDateTime();

        $this->assertInstanceOf(\DateTime::class, $dateTimeClass);
        $this->assertEquals('2017-01-01 00:00:00', $dateTimeClass->format('Y-m-d H:i:s'));
    }

    /**
     * Test getUnixTimestamp method.
     */
    public function testGetUnixTimestamp()
    {
        $date = $this->getDate();
        $time = $this->getTime();

        $dateTime = new DateTime($date, $time);

        $timeStamp = $dateTime->getUnixTimestamp();

        $this->assertInternalType('int', $timeStamp);
        $this->assertEquals(1483228800, $timeStamp);
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $date = $this->getDate();
        $time = $this->getTime();

        $dateTime = new DateTime($date, $time);

        $this->assertEquals('2017-01-01 00:00:00', $dateTime->getValue());
    }

    /**
     * Test __toString method.
     */
    public function testToString()
    {
        $date = $this->getDate();
        $time = $this->getTime();

        $dateTime = new DateTime($date, $time);

        $this->assertEquals('2017-01-01 00:00:00', (string)$dateTime);
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $date = $this->getDate();
        $time = $this->getTime();

        $dateTime = new DateTime($date, $time);

        $this->assertEquals('2017-01-01 00:00:00', $dateTime->jsonSerialize());
    }
}
