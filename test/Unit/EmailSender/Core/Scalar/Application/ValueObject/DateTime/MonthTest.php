<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Month;
use PHPUnit\Framework\TestCase;

/**
 * Class MonthTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class MonthTest extends TestCase
{
    /**
     * Test __construct with valid values.
     */
    public function testConstructWithValidValues()
    {
        $withLowerLimitValue = new Month(Month::LIMIT_LOWER);
        $withPositiveValue   = new Month(1);
        $withUpperLimitValue = new Month(Month::LIMIT_UPPER);

        $this->assertInstanceOf(Month::class, $withLowerLimitValue);
        $this->assertInstanceOf(Month::class, $withPositiveValue);
        $this->assertInstanceOf(Month::class, $withUpperLimitValue);
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
        new Month($value);
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
        new Month($value);
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $int   = 1;
        $month = new Month($int);

        $this->assertEquals($int, $month->getValue());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $int   = 1;
        $month = new Month($int);

        $this->assertEquals($int, $month->jsonSerialize());
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
                13
            ],
        ];
    }
}
