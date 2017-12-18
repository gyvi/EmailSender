<?php

namespace Test\Unit\EmailSender\Core\ValueObject;

use EmailSender\Core\ValueObject\Name;
use PHPUnit\Framework\TestCase;

/**
 * Class NameTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class NameTest extends TestCase
{
    /**
     * Test __construct with valid values.
     *
     * @param string $value
     *
     * @dataProvider providerForTestConstructWithValidValues
     */
    public function testConstructWithValidValues(string $value)
    {
        $name = new Name($value);

        $this->assertInstanceOf(Name::class, $name);
    }

    /**
     * Test __construct with values which doesn't match with the pattern.
     *
     * @param string $value
     *
     * @dataProvider providerForTestConstructWithNotMatchedValues
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage Invalid Name. The given value doesn't match with the pattern.
     */
    public function testConstructWithNotMatchedValues(string $value)
    {
        new Name($value);
    }

    /**
     * Test __construct with too short value.
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage Invalid Name. String is too short!
     */
    public function testConstructWithTooShortValue()
    {
        new Name('');
    }

    /**
     * Test __construct with too long value.
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage Invalid Name. String is too long!
     */
    public function testConstructWithTooLongValue()
    {
        new Name('01234567890123456789012345678901234567890123456789012345678901234');
    }

    /**
     * Test __toString method.
     */
    public function testToString()
    {
        $nameValue = 'testName';

        $name = new Name($nameValue);

        $this->assertEquals($nameValue, (string)$name);
    }

    /**
     * Data provider for testConstructWithValidValues.
     *
     * @return array
     */
    public function providerForTestConstructWithValidValues(): array
    {
        return [
            ['test name'],
            ["Dr. ÓÜÖÚŐŰÁÉ, el'r-kfelkf"],
        ];
    }

    /**
     * Data provider for testConstructWithNotMatchedValues.
     *
     * @return array
     */
    public function providerForTestConstructWithNotMatchedValues(): array
    {
        return [
            ['asd €'],
            ['dfdfg
            dfgdg'],
        ];
    }
}
