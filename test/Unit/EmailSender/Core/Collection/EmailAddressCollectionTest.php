<?php

namespace Test\Unit\EmailSender\Core\Collection;

use EmailSender\Core\Collection\EmailAddressCollection;
use EmailSender\Core\ValueObject\EmailAddress;
use PHPUnit\Framework\TestCase;
use ArrayIterator;

/**
 * Class EmailAddressCollectionTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class EmailAddressCollectionTest extends TestCase
{
    /**
     * @return \EmailSender\Core\ValueObject\EmailAddress|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getMailAddress()
    {
        /** @var \EmailSender\Core\ValueObject\EmailAddress|\PHPUnit_Framework_MockObject_MockObject $emailAddress */
        $emailAddress = $this->getMockBuilder(EmailAddress::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $emailAddress;
    }

    /**
     * Test getType method.
     */
    public function testGetType()
    {
        $emailAddressCollection = new EmailAddressCollection();

        $this->assertEquals(EmailAddress::class, $emailAddressCollection->getType());
    }

    /**
     * Test add method with valid value.
     */
    public function testAddWithValidValue()
    {
        $emailAddress = $this->getMailAddress();

        $emailAddressCollection = new EmailAddressCollection();

        $emailAddressCollection->add($emailAddress);
        $emailAddressCollection->add($emailAddress);

        $this->assertEquals([$emailAddress, $emailAddress], $emailAddressCollection->toArray());
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
        $emailAddressCollection = new EmailAddressCollection();

        $emailAddressCollection->add($invalidType);
    }

    /**
     * Test count and isEmpty methods.
     */
    public function testCountAndIsEmpty()
    {
        $emailAddress = $this->getMailAddress();

        $emailAddressCollection = new EmailAddressCollection();

        $this->assertEquals(0, $emailAddressCollection->count());
        $this->assertTrue($emailAddressCollection->isEmpty());

        $emailAddressCollection->add($emailAddress);
        $emailAddressCollection->add($emailAddress);

        $this->assertEquals(2, $emailAddressCollection->count());
        $this->assertFalse($emailAddressCollection->isEmpty());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $emailAddress = $this->getMailAddress();

        $emailAddressCollection = new EmailAddressCollection();

        $emailAddressCollection->add($emailAddress);
        $emailAddressCollection->add($emailAddress);

        $this->assertEquals([$emailAddress, $emailAddress], $emailAddressCollection->jsonSerialize());
    }

    /**
     * Test getIterator method.
     */
    public function testGetIterator()
    {
        $emailAddress = $this->getMailAddress();

        $emailAddressCollection = new EmailAddressCollection();

        $emailAddressCollection->add($emailAddress);
        $emailAddressCollection->add($emailAddress);

        $this->assertEquals(
            new ArrayIterator([$emailAddress, $emailAddress]),
            $emailAddressCollection->getIterator()
        );
    }

    /**
     * Data provider for testAddWithInvalidValues.
     *
     * @return array
     */
    public function providerForTestAddWithInvalidValues(): array
    {
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
