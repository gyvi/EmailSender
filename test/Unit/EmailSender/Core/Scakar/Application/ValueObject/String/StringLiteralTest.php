<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\ValueObject\String;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use PHPUnit\Framework\TestCase;

/**
 * Class StringLiteralTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class StringLiteralTest extends TestCase
{
    /**
     * Test __construct with valid value.
     */
    public function testConstructWithValidValues()
    {
        $stringLiteral = new StringLiteral('test string');

        $this->assertInstanceOf(StringLiteral::class, $stringLiteral);
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
        new StringLiteral($value);

        $this->fail();
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $string = 'test string';

        $stringLiteral = new StringLiteral($string);

        $this->assertEquals($string, $stringLiteral->getValue());
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
                1
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
}
