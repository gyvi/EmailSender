<?php

namespace Test\Unit\EmailSender\MailAddress\Application\Collection;

use EmailSender\MailAddress\Application\Collection\MailAddressCollection;
use PHPUnit\Framework\TestCase;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use ArrayIterator;

/**
 * Class MailAddressCollectionTest
 *
 * @package Test\Unit\EmailSender\MailAddress
 */
class MailAddressCollectionTest extends TestCase
{
    /**
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getMailAddressMock()
    {
        /** @var MailAddress|\PHPUnit_Framework_MockObject_MockObject $mailAddressMock */
        $mailAddressMock = $this->getMockBuilder(MailAddress::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $mailAddressMock;
    }

    /**
     * Test __construct method.
     */
    public function testConstruct()
    {
        $mailAddressCollection = new MailAddressCollection();

        $this->assertInstanceOf(MailAddressCollection::class, $mailAddressCollection);
    }

    /**
     * Test getType method.
     */
    public function testGetType()
    {
        $mailAddressCollection = new MailAddressCollection();

        $this->assertEquals(MailAddress::class, $mailAddressCollection->getType());
    }

    /**
     * Test add method with valid value.
     */
    public function testAddWithValidValue()
    {
        $mailAddressMock = $this->getMailAddressMock();

        $mailAddressCollection = new MailAddressCollection();

        $mailAddressCollection->add($mailAddressMock);
        $mailAddressCollection->add($mailAddressMock);

        $this->assertEquals([$mailAddressMock, $mailAddressMock], $mailAddressCollection->toArray());
    }

    /**
     * @param $invalidType
     *
     * @dataProvider providerForTestAddWithInvalidValues
     *
     * @expectedException \InvalidArgumentException
     */
    public function testAddWithInvalidValues($invalidType)
    {
        $mailAddressCollection = new MailAddressCollection();

        $mailAddressCollection->add($invalidType);

        $this->fail();
    }

    /**
     * Test count and isEmpty methods.
     */
    public function testCountAndIsEmpty()
    {
        $mailAddressMock = $this->getMailAddressMock();

        $mailAddressCollection = new MailAddressCollection();

        $this->assertEquals(0, $mailAddressCollection->count());
        $this->assertTrue($mailAddressCollection->isEmpty());

        $mailAddressCollection->add($mailAddressMock);
        $mailAddressCollection->add($mailAddressMock);

        $this->assertEquals(2,$mailAddressCollection->count());
        $this->assertFalse($mailAddressCollection->isEmpty());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $mailAddressMock = $this->getMailAddressMock();

        $mailAddressCollection = new MailAddressCollection();

        $mailAddressCollection->add($mailAddressMock);
        $mailAddressCollection->add($mailAddressMock);

        $this->assertEquals([$mailAddressMock, $mailAddressMock], $mailAddressCollection->jsonSerialize());
    }

    public function testGetIterator()
    {
        $mailAddressMock = $this->getMailAddressMock();

        $mailAddressCollection = new MailAddressCollection();

        $mailAddressCollection->add($mailAddressMock);
        $mailAddressCollection->add($mailAddressMock);

        $this->assertEquals(
            new ArrayIterator([$mailAddressMock, $mailAddressMock]),
            $mailAddressCollection->getIterator()
        );
    }

    /**
     * Data provider for testAddWithInvalidValues.
     *
     * @return array
     */
    public function providerForTestAddWithInvalidValues() {
        return [
            [
                null,
            ],
            [
                new \stdClass(),
            ],
            [
                'string',
            ],
            [
                true,
            ],
            [
                1,
            ],
            [
                1.1,
            ],
        ];
    }
}
