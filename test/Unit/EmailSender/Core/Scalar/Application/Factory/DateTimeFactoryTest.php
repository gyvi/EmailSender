<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\Factory;

use EmailSender\Core\Scalar\Application\Factory\DateTimeFactory;
use EmailSender\Core\Scalar\Application\ValueObject\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class DateTimeFactoryTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class DateTimeFactoryTest extends TestCase
{
    /**
     * Test createFromNative method.
     */
    public function testCreateFromNative()
    {
        $dateTimeFactory = new DateTimeFactory();

        $dateTime = $dateTimeFactory->createFromNative(2017, 1, 1, 0, 0, 0);

        $this->assertInstanceOf(DateTime::class, $dateTime);
    }

    /**
     * Test createFromDateTime method.
     */
    public function testCreateFromDateTime()
    {
        $dateTimeFactory = new DateTimeFactory();

        $dateTime = $dateTimeFactory->createFromDateTime(new \DateTime());

        $this->assertInstanceOf(DateTime::class, $dateTime);
    }
}
