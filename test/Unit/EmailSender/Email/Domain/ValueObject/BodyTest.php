<?php

namespace Test\Unit\EmailSender\Email\Domain\ValueObject;

use EmailSender\Email\Domain\ValueObject\Body;
use PHPUnit\Framework\TestCase;

/**
 * Class BodyTest
 *
 * @package Test\Unit\EmailSender\Email
 */
class BodyTest extends TestCase
{
    /**
     * Test __construct with valid value.
     */
    public function testConstructWithValidValues()
    {
        $body = new Body('test string');

        $this->assertInstanceOf(Body::class, $body);
    }

    /**
     * Test __construct with invalid string length.
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    public function testConstructWithInvalidLength()
    {
        new Body('');
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
        new Body($value);
    }

    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $string = 'test string';

        $body = new Body($string);

        $this->assertEquals($string, $body->getValue());
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
