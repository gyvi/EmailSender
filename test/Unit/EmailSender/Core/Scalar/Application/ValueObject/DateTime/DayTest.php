<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Day;
use PHPUnit\Framework\TestCase;

/**
 * Class DayTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class DayTest extends TestCase
{
    /**
     * Test __construct with valid values.
     */
    public function testConstructWithValidValues()
    {
        $withLowerLimitValue = new Day(Day::LIMIT_LOWER);
        $withPositiveValue   = new Day(1);
        $withUpperLimitValue = new Day(Day::LIMIT_UPPER);

        $this->assertInstanceOf(Day::class, $withLowerLimitValue);
        $this->assertInstanceOf(Day::class, $withPositiveValue);
        $this->assertInstanceOf(Day::class, $withUpperLimitValue);
    }

    /**
     * Test __construct with invalid values.
     *
     * @param mixed $value
     *
     * @dataProvider providerForTestConstructWithInvalidValues
     *
     * @expectedException \TypeError
     */
    public function testConstructWithInvalidValues(mixed $value)
    {
        new Day($value);
    }

    /**
     * Test __construct with out of range values.
     *
     * @param int $value
     *
     * @dataProvider providerForTestConstructWithOutOfRangeValues
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    public function testConstructWithOutOfRangeValues(int $value)
    {
        new Day($value);
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $int = 1;
        $day = new Day($int);

        $this->assertEquals($int, $day->getValue());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $int = 1;
        $day = new Day($int);

        $this->assertEquals($int, $day->jsonSerialize());
    }

    /**
     * Data provider for testConstructWithInvalidValues.
     *
     * @return array
     */
    public function providerForTestConstructWithInvalidValues(): array
    {
        return [
            [
                'string'
            ],
            [
                1.123123
            ],
            [
                ['text']
            ],
            [
                new \StdClass()
            ],
            [
                null
            ],
            [
                true
            ],
            [
                function () {}
            ],
        ];
    }

    /**
     * Data provider for testConstructWithOutOfRangeValues.
     *
     * @return array
     */
    public function providerForTestConstructWithOutOfRangeValues(): array
    {
        return [
            [
                0
            ],
            [
                32
            ],
        ];
    }
}
