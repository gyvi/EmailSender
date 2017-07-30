<?php

namespace Test\Unit\EmailSender\Core\ValueObject;

use EmailSender\Core\ValueObject\Address;
use PHPUnit\Framework\TestCase;

/**
 * Class AddressTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class AddressTest extends TestCase
{
    /**
     * Test __construct with valid value.
     */
    public function testConstructWithValidValue()
    {
        $address = new Address('test@test.org');

        $this->assertInstanceOf(Address::class, $address);
    }

    /**
     * Test __construct with invalid values.
     *
     * @param string $value
     *
     * @dataProvider providerForTestConstructWithInvalidValues
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage Invalid Address. Wrong email address.
     */
    public function testConstructWithInvalidValues(string $value)
    {
        new Address($value);

        $this->fail();
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
                'something',
            ],
            [
                'something@',
            ],
            [
                'something@something',
            ],
        ];
    }
}
