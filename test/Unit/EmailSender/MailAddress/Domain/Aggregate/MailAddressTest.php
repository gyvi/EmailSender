<?php

namespace Test\Unit\EmailSender\MailAddress\Domain\Aggregate;

use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\DisplayName;
use PHPUnit\Framework\TestCase;

/**
 * Class MailAddressTest
 *
 * @package Test\Unit\EmailSender\MailAddress
 */
class MailAddressTest extends TestCase
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
     * Test __construct with valid values.
     *
     * @param \EmailSender\Core\ValueObject\Address          $address
     * @param \EmailSender\Core\ValueObject\DisplayName|null $displayName
     *
     * @dataProvider providerForTestConstructWithValidValues
     */
    public function testConstructWithValidValues(Address $address, ?DisplayName $displayName)
    {
        $mailAddress = new MailAddress($address, $displayName);

        $this->assertInstanceOf(MailAddress::class, $mailAddress);
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
        new MailAddress($address, $displayName);

        $this->fail();
    }

    /**
     * Test getAddress method.
     */
    public function testGetAddress()
    {
        $address     = $this->getAddressMock();
        $displayName = $this->getDisplayNameMock();

        $mailAddress = new MailAddress($address, $displayName);

        $this->assertEquals($address, $mailAddress->getAddress());
    }

    /**
     * Test getDisplayName method.
     */
    public function testGetDisplayName()
    {
        $address     = $this->getAddressMock();
        $displayName = $this->getDisplayNameMock();

        $mailAddress = new MailAddress($address, $displayName);

        $this->assertEquals($displayName, $mailAddress->getDisplayName());
    }

    /**
     * Test getDisplayName method with null value.
     */
    public function testGetDisplayNameWithNullValue()
    {
        $address     = $this->getAddressMock();

        $mailAddress = new MailAddress($address, null);

        $this->assertNull($mailAddress->getDisplayName());
    }

    /**
     * Data provider for testConstructWithValidValues.
     *
     * @return array
     */
    public function providerForTestConstructWithValidValues()
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
    public function providerForTestConstructWithInvalidValues()
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
