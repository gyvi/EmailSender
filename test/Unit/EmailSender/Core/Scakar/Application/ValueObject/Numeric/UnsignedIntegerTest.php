<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\ValueObject\Numeric;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use PHPUnit\Framework\TestCase;

/**
 * Class UnsignedIntegerTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class UnsignedIntegerTest extends TestCase
{
    /**
     * Test __construct with valid values.
     */
    public function testConstructWithValidValues()
    {
        $withLowerLimitValue = new UnsignedInteger(UnsignedInteger::LIMIT_LOWER);
        $withPositiveValue   = new UnsignedInteger(1);
        $withUpperLimitValue = new UnsignedInteger(UnsignedInteger::LIMIT_UPPER);

        $this->assertInstanceOf(UnsignedInteger::class, $withLowerLimitValue);
        $this->assertInstanceOf(UnsignedInteger::class, $withPositiveValue);
        $this->assertInstanceOf(UnsignedInteger::class, $withUpperLimitValue);
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
        new UnsignedInteger($value);

        $this->fail();
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
        new UnsignedInteger($value);

        $this->fail();
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $int             = 1;
        $unsignedInteger = new UnsignedInteger($int);

        $this->assertEquals($int, $unsignedInteger->getValue());
    }

    /**
     * Data provider for testConstructWithInvalidValues.
     *
     * @return array
     */
    public function providerForTestConstructWithInvalidValues()
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
    public function providerForTestConstructWithOutOfRangeValues()
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
