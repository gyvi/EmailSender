<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\ValueObject\DateTime\Second;
use PHPUnit\Framework\TestCase;

/**
 * Class SecondTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class SecondTest extends TestCase
{
    /**
     * Test __construct with valid values.
     */
    public function testConstructWithValidValues()
    {
        $withLowerLimitValue = new Second(Second::LIMIT_LOWER);
        $withPositiveValue   = new Second(1);
        $withUpperLimitValue = new Second(Second::LIMIT_UPPER);

        $this->assertInstanceOf(Second::class, $withLowerLimitValue);
        $this->assertInstanceOf(Second::class, $withPositiveValue);
        $this->assertInstanceOf(Second::class, $withUpperLimitValue);
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
        new Second($value);
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
        new Second($value);
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $int    = 1;
        $second = new Second($int);

        $this->assertEquals($int, $second->getValue());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $int    = 1;
        $second = new Second($int);

        $this->assertEquals($int, $second->jsonSerialize());
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
