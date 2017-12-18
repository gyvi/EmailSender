<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Minute;
use PHPUnit\Framework\TestCase;

/**
 * Class MinuteTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class MinuteTest extends TestCase
{
    /**
     * Test __construct with valid values.
     */
    public function testConstructWithValidValues()
    {
        $withLowerLimitValue = new Minute(Minute::LIMIT_LOWER);
        $withPositiveValue   = new Minute(1);
        $withUpperLimitValue = new Minute(Minute::LIMIT_UPPER);

        $this->assertInstanceOf(Minute::class, $withLowerLimitValue);
        $this->assertInstanceOf(Minute::class, $withPositiveValue);
        $this->assertInstanceOf(Minute::class, $withUpperLimitValue);
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
        new Minute($value);
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
        new Minute($value);
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $int    = 1;
        $minute = new Minute($int);

        $this->assertEquals($int, $minute->getValue());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $int    = 1;
        $minute = new Minute($int);

        $this->assertEquals($int, $minute->jsonSerialize());
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
                60
            ],
        ];
    }
}
