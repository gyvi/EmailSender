<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Year;
use PHPUnit\Framework\TestCase;

/**
 * Class YearTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class YearTest extends TestCase
{
    /**
     * Test __construct with valid values.
     */
    public function testConstructWithValidValues()
    {
        $withLowerLimitValue = new Year(Year::LIMIT_LOWER);
        $withPositiveValue   = new Year(1);
        $withUpperLimitValue = new Year(Year::LIMIT_UPPER);

        $this->assertInstanceOf(Year::class, $withLowerLimitValue);
        $this->assertInstanceOf(Year::class, $withPositiveValue);
        $this->assertInstanceOf(Year::class, $withUpperLimitValue);
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
        new Year($value);
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
        new Year($value);
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $int  = 1;
        $year = new Year($int);

        $this->assertEquals($int, $year->getValue());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $int  = 1;
        $year = new Year($int);

        $this->assertEquals($int, $year->jsonSerialize());
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
                -1
            ],
            [
                PHP_INT_MIN
            ],
        ];
    }
}
