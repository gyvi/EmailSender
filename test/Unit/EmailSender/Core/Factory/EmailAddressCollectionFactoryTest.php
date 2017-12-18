<?php

namespace Test\Unit\EmailSender\Core\Factory;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Collection\EmailAddressCollection;
use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\EmailAddress;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailAddressCollectionFactoryTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class EmailAddressCollectionFactoryTest extends TestCase
{
    /**
     * @return \EmailSender\Core\Factory\EmailAddressFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getEmailAddressFactory()
    {
        /** @var \EmailSender\Core\Factory\EmailAddressFactory|\PHPUnit_Framework_MockObject_MockObject $emailAddressFactory */
        $emailAddressFactory = $this->getMockBuilder(EmailAddressFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $emailAddressFactory;
    }

    /**
     * Test buildMailAddressCollectionFromString with valid values.
     *
     * @param string $emailAddressCollectionString
     *
     * @dataProvider providerForTestCreateFromStringWithValidValues
     */
    public function testCreateFromStringWithValidValues(
        string $emailAddressCollectionString
    ) {
        $emailAddressFactory = $this->getEmailAddressFactory();

        $emailAddressFactory->expects($this->any())
            ->method('create')
            ->willReturn(new EmailAddress(new Address('test@test.hu'), null));

        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);

        $this->assertInstanceOf(
            EmailAddressCollection::class,
            $emailAddressCollectionFactory->createFromString($emailAddressCollectionString)
        );
    }

    /**
     * Test buildMailAddressCollectionFromString with Exception (TypeError etc...).
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     *
     * @expectedExceptionMessage  Error message
     */
    public function testCreateFromStringWithTypeError()
    {
        $emailAddressFactory = $this->getEmailAddressFactory();

        $emailAddressFactory->expects($this->once())
            ->method('create')
            ->willThrowException(new ValueObjectException('Error message'));

        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);

        $emailAddressCollectionFactory->createFromString('test');
    }

    /**
     * Test createFromArray with valid values.
     *
     * @param array $emailAddressCollectionArray
     *
     * @dataProvider providerForTestCreateFromArrayWithValidValues
     */
    public function testCreateFromArrayWithValidValues(array $emailAddressCollectionArray)
    {
        $emailAddressFactory = $this->getEmailAddressFactory();

        $emailAddressFactory->expects($this->any())
            ->method('createFromArray')
            ->willReturn((new Mockery($this))->getEmailAddressMock());

        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);

        $emailAddressCollection = $emailAddressCollectionFactory->createFromArray($emailAddressCollectionArray);

        $this->assertInstanceOf(EmailAddressCollection::class, $emailAddressCollection);
        $this->assertEquals(count($emailAddressCollectionArray), $emailAddressCollection->count());
    }

    /**
     * Test createFromArray with invalid value.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid EmailAddress.
     */
    public function testCreateFromArrayWithInvalidValue()
    {
        $emailAddressFactory = $this->getEmailAddressFactory();

        $emailAddressFactory->expects($this->any())
            ->method('createFromArray')
            ->willThrowException(new ValueObjectException('error message'));

        $emailAddressCollectionFactory = new EmailAddressCollectionFactory($emailAddressFactory);

        $emailAddressCollectionFactory->createFromArray([[]]);
    }

    /**
     * Provider for testCreateFromArrayWithValidValues.
     *
     * @return array
     */
    public function providerForTestCreateFromArrayWithValidValues(): array
    {
        return [
            [
                [
                    [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress1',
                    ],
                ],
            ],
            [
                [
                    [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress1',
                    ],
                    [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress2',
                    ],
                    [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress3',
                    ],
                ],
            ],
        ];
    }

    /**
     * Provider for testCreateFromStringWithValidValues.
     *
     * @return array
     */
    public function providerForTestCreateFromStringWithValidValues(): array
    {
        return [
            [
                'test@test.hu, Test test <test@test.com>, "Test test" <test@test.lu>',
            ],
            [
                'test@test.hu, Test test <test@test.com>',
            ],
        ];
    }
}
