<?php

namespace Test\Unit\EmailSender\Core\ValueObject;

use EmailSender\Core\ValueObject\EmailAddress;
use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\Name;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailAddressTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class EmailAddressTest extends TestCase
{
    /**
     * @return \EmailSender\Core\ValueObject\Address|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getAddressMock()
    {
        /** @var Address|\PHPUnit_Framework_MockObject_MockObject $address */
        $address = $this->getMockBuilder(Address::class)->disableOriginalConstructor()->getMock();

        return $address;
    }

    /**
     * @return \EmailSender\Core\ValueObject\Name|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getNameMock()
    {
        /** @var Name|\PHPUnit_Framework_MockObject_MockObject $name */
        $name = $this->getMockBuilder(Name::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $name;
    }

    /**
     * Test __construct with invalid values.
     *
     * @param mixed $address
     * @param mixed $name
     *
     * @dataProvider providerForTestConstructWithInvalidValues
     *
     * @expectedException \TypeError
     */
    public function testConstructWithInvalidValues($address, $name)
    {
        new EmailAddress($address, $name);
    }

    /**
     * Test getAddress method.
     */
    public function testGetAddress()
    {
        $address = $this->getAddressMock();
        $name    = $this->getNameMock();

        $mailAddress = new EmailAddress($address, $name);

        $this->assertEquals($address, $mailAddress->getAddress());
    }

    /**
     * Test getName method.
     */
    public function testGetName()
    {
        $address     = $this->getAddressMock();
        $name = $this->getNameMock();

        $mailAddress = new EmailAddress($address, $name);

        $this->assertEquals($name, $mailAddress->getName());
    }

    /**
     * Test getName method with null value.
     */
    public function testGetNameWithNullValue()
    {
        $address     = $this->getAddressMock();

        $mailAddress = new EmailAddress($address, null);

        $this->assertNull($mailAddress->getName());
    }

    /**
     * Data provider for testConstructWithValidValues.
     *
     * @return array
     */
    public function providerForTestConstructWithValidValues(): array
    {
        $address = $this->getAddressMock();
        $name    = $this->getNameMock();

        return [
            [
                $address,
                $name,
            ],
            [
                $address,
                null
            ],
        ];
    }

    /**
     * Data provider for testConstructWithInvalidValues.
     *
     * @return array
     */
    public function providerForTestConstructWithInvalidValues(): array
    {
        $address = $this->getAddressMock();
        $name    = $this->getNameMock();

        return [
            [
                'string',
                $name,
            ],
            [
                $address,
                ''
            ],
        ];
    }
}
