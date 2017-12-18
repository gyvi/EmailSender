<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Hour;
use PHPUnit\Framework\TestCase;

class HourTest extends TestCase
{
    /**
     * Test __construct with valid values.
     */
    public function testConstructWithValidValues()
    {
        $withLowerLimitValue = new Hour(Hour::LIMIT_LOWER);
        $withPositiveValue   = new Hour(1);
        $withUpperLimitValue = new Hour(Hour::LIMIT_UPPER);

        $this->assertInstanceOf(Hour::class, $withLowerLimitValue);
        $this->assertInstanceOf(Hour::class, $withPositiveValue);
        $this->assertInstanceOf(Hour::class, $withUpperLimitValue);
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
        new Hour($value);
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
        new Hour($value);
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $int  = 1;
        $hour = new Hour($int);

        $this->assertEquals($int, $hour->getValue());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $int  = 1;
        $hour = new Hour($int);

        $this->assertEquals($int, $hour->jsonSerialize());
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
                24
            ],
        ];
    }
}
