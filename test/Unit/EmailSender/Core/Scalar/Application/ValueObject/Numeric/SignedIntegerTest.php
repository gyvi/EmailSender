<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\ValueObject\Numeric;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\SignedInteger;
use PHPUnit\Framework\TestCase;

/**
 * Class SignedIntegerTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class SignedIntegerTest extends TestCase
{
    /**
     * Test __construct with valid values.
     */
    public function testConstructWithValidValues()
    {
        $withLowerLimitValue = new SignedInteger(SignedInteger::LIMIT_LOWER);
        $withPositiveValue   = new SignedInteger(1);
        $withUpperLimitValue = new SignedInteger(SignedInteger::LIMIT_UPPER);

        $this->assertInstanceOf(SignedInteger::class, $withLowerLimitValue);
        $this->assertInstanceOf(SignedInteger::class, $withPositiveValue);
        $this->assertInstanceOf(SignedInteger::class, $withUpperLimitValue);
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
        new SignedInteger($value);
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $int             = 1;
        $SignedInteger = new SignedInteger($int);

        $this->assertEquals($int, $SignedInteger->getValue());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $int             = 1;
        $SignedInteger = new SignedInteger($int);

        $this->assertEquals($int, $SignedInteger->jsonSerialize());
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
            [
                PHP_INT_MAX + 1
            ],
            [
                PHP_INT_MIN -1
            ],
        ];
    }
}
