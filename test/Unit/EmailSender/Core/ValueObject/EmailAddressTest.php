<?php

namespace Test\Unit\EmailSender\Core\ValueObject;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\ValueObject\EmailAddress;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

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
        return (new Mockery($this))->getAddressMock('');
    }

    /**
     * @return \EmailSender\Core\ValueObject\Name|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getNameMock()
    {
        return (new Mockery($this))->getNameMock('');
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

        $emailAddress = new EmailAddress($address, $name);

        $this->assertEquals($address, $emailAddress->getAddress());
    }

    /**
     * Test getName method.
     */
    public function testGetName()
    {
        $address = $this->getAddressMock();
        $name    = $this->getNameMock();

        $emailAddress = new EmailAddress($address, $name);

        $this->assertEquals($name, $emailAddress->getName());
    }

    /**
     * Test getName method with null value.
     */
    public function testGetNameWithNullValue()
    {
        $address     = $this->getAddressMock();
        $emailAddress = new EmailAddress($address, null);

        $this->assertNull($emailAddress->getName());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $address = $this->getAddressMock();
        $name    = $this->getNameMock();

        $expected = [
            EmailAddressPropertyNameList::ADDRESS => $address,
            EmailAddressPropertyNameList::NAME    => $name,
        ];

        $emailAddress = new EmailAddress($address, $name);

        $this->assertEquals($expected, $emailAddress->jsonSerialize());
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
