<?php

namespace Test\Unit\EmailSender\Core\Factory;

use EmailSender\Core\Collection\EmailAddressCollection;
use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\EmailAddress;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailAddressCollectionFactoryTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class EmailAddressCollectionFactoryTest extends TestCase
{
    /**
     * @return \EmailSender\Core\ValueObject\EmailAddress|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getEmailAddress()
    {
        /** @var \EmailSender\Core\ValueObject\EmailAddress|\PHPUnit_Framework_MockObject_MockObject $emailAddress */
        $emailAddress = $this->getMockBuilder(EmailAddress::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $emailAddress;
    }

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
