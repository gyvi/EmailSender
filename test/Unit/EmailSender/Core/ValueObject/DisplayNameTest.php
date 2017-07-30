<?php

namespace Test\Unit\EmailSender\Core\ValueObject;

use EmailSender\Core\ValueObject\DisplayName;
use PHPUnit\Framework\TestCase;

/**
 * Class DisplayNameTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class DisplayNameTest extends TestCase
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
        $displayName = new DisplayName($value);

        $this->assertInstanceOf(DisplayName::class, $displayName);
    }

    /**
     * Test __construct with values which doesn't match with the pattern.
     *
     * @param string $value
     *
     * @dataProvider providerForTestConstructWithNotMatchedValues
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage Invalid DisplayName. The given value doesn't match with the pattern.
     */
    public function testConstructWithNotMatchedValues(string $value)
    {
        new DisplayName($value);

        $this->fail();
    }

    /**
     * Test __construct with too short value.
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage Invalid DisplayName. String is too short!
     */
    public function testConstructWithTooShortValue()
    {
        new DisplayName('');

        $this->fail();
    }

    /**
     * Test __construct with too long value.
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage Invalid DisplayName. String is too long!
     */
    public function testConstructWithTooLongValue()
    {
        new DisplayName('01234567890123456789012345678901234567890123456789012345678901234');

        $this->fail();
    }

    /**
     * Data provider for testConstructWithValidValues.
     *
     * @return array
     */
    public function providerForTestConstructWithValidValues()
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
    public function providerForTestConstructWithNotMatchedValues()
    {
        return [
            ["asd +"],
            ["dfdfg
            dfgdg"],
        ];
    }
}
