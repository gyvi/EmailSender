<?php

namespace Test\Unit\EmailSender\Core\Factory;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\ValueObject\EmailAddress;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailAddressFactoryTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class EmailAddressFactoryTest extends TestCase
{
    /**
     * Test buildMailAddressFromString with valid string.
     *
     * @param string      $emailAddressString
     * @param string      $expectedAddress
     * @param string|null $expectedName
     *
     * @dataProvider providerForTestCreateWithValidValues
     */
    public function testCreateWithValidValues(
        string $emailAddressString,
        string $expectedAddress,
        ?string $expectedName
    ) {
        $emailAddressFactory = new EmailAddressFactory();

        $emailAddress = $emailAddressFactory->create($emailAddressString);

        $address = $emailAddress->getAddress();
        $name    = $emailAddress->getName();

        if ($name) {
            $name = $name->getValue();
        }

        $this->assertEquals($expectedAddress, $address->getValue());
        $this->assertEquals($expectedName, $name);
    }

    /**
     * Test create with invalid values. (More than one email address.)
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage Invalid email address.
     */
    public function testCreateWithInvalidValues() {
        $emailAddressFactory = new EmailAddressFactory();

        $emailAddressFactory->create('John Doe <test@test.com>, John Doe2 <test2@test.com>');
    }

    /**
     * Test createFromArray method.
     *
     * @param array $emailAddressArray
     *
     * @dataProvider providerForTestCreateFromArrayWithValidValues
     */
    public function testCreateFromArrayWithValidValues(array $emailAddressArray)
    {
        $emailAddressFactory = new EmailAddressFactory();

        $emailAddress = $emailAddressFactory->createFromArray($emailAddressArray);

        $this->assertInstanceOf(EmailAddress::class, $emailAddress);

        if (!empty($emailAddressArray[EmailAddressPropertyNameList::NAME])) {
            $this->assertEquals(
                $emailAddressArray[EmailAddressPropertyNameList::NAME],
                $emailAddress->getName()->getValue()
            );
        }

        $this->assertEquals(
            $emailAddressArray[EmailAddressPropertyNameList::ADDRESS],
            $emailAddress->getAddress()->getValue()
        );
    }

    /**
     * Test createFromArray with with invalid value.
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    public function testCreateFromArrayWithInvalidValue()
    {
        $emailAddressFactory = new EmailAddressFactory();

        $emailAddressFactory->createFromArray([
            EmailAddressPropertyNameList::ADDRESS => 'test',
        ]);
    }

    /**
     * Data provider for testCreateWithValidValues.
     *
     * @return array
     */
    public function providerForTestCreateWithValidValues(): array
    {
        return [
            [
                'John Doe <test@test.com>',
                'test@test.com',
                'John Doe',
            ],
            [
                ' "John Doe" <test@test.com>',
                'test@test.com',
                'John Doe',
            ],
            [
                '  <test@test.com>',
                'test@test.com',
                null,
            ],
            [
                '<test@test.com>',
                'test@test.com',
                null,
            ],
            [
                ' test@test.com ',
                'test@test.com',
                null,
            ],
            [
                '"test@test.com"',
                'test@test.com',
                null,
            ],
            [
                'test@test.com',
                'test@test.com',
                null,
            ],
        ];
    }

    /**
     * Data provider for testCreateFromArrayWithValidValues method.
     *
     * @return array
     */
    public function providerForTestCreateFromArrayWithValidValues(): array
    {
        return [
            [
                [
                    EmailAddressPropertyNameList::ADDRESS => 'test@test.com',
                    EmailAddressPropertyNameList::NAME    => 'John Doe',
                ],
                [
                    EmailAddressPropertyNameList::ADDRESS => 'test@test.com',
                    EmailAddressPropertyNameList::NAME    => null,
                ],
                [
                    EmailAddressPropertyNameList::ADDRESS => 'test@test.com',
                ],
            ],

        ];
    }
}
