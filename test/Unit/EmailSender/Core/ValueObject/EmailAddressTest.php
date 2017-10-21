<?php

namespace Test\Unit\EmailSender\Core\ValueObject;

use EmailSender\Core\ValueObject\EmailAddress;
use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\DisplayName;
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
     * @return \EmailSender\Core\ValueObject\DisplayName|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getDisplayNameMock()
    {
        /** @var DisplayName|\PHPUnit_Framework_MockObject_MockObject $displayName */
        $displayName = $this->getMockBuilder(DisplayName::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $displayName;
    }

    /**
     * Test __construct with invalid values.
     *
     * @param mixed $address
     * @param mixed $displayName
     *
     * @dataProvider providerForTestConstructWithInvalidValues
     *
     * @expectedException \TypeError
     */
    public function testConstructWithInvalidValues($address, $displayName)
    {
        new EmailAddress($address, $displayName);
    }

    /**
     * Test getAddress method.
     */
    public function testGetAddress()
    {
        $address     = $this->getAddressMock();
        $displayName = $this->getDisplayNameMock();

        $mailAddress = new EmailAddress($address, $displayName);

        $this->assertEquals($address, $mailAddress->getAddress());
    }

    /**
     * Test getDisplayName method.
     */
    public function testGetDisplayName()
    {
        $address     = $this->getAddressMock();
        $displayName = $this->getDisplayNameMock();

        $mailAddress = new EmailAddress($address, $displayName);

        $this->assertEquals($displayName, $mailAddress->getDisplayName());
    }

    /**
     * Test getDisplayName method with null value.
     */
    public function testGetDisplayNameWithNullValue()
    {
        $address     = $this->getAddressMock();

        $mailAddress = new EmailAddress($address, null);

        $this->assertNull($mailAddress->getDisplayName());
    }

    /**
     * Data provider for testConstructWithValidValues.
     *
     * @return array
     */
    public function providerForTestConstructWithValidValues(): array
    {
        $address     = $this->getAddressMock();
        $displayName = $this->getDisplayNameMock();

        return [
            [
                $address,
                $displayName,
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
        $address     = $this->getAddressMock();
        $displayName = $this->getDisplayNameMock();

        return [
            [
                'string',
                $displayName,
            ],
            [
                $address,
                ''
            ],
        ];
    }
}
