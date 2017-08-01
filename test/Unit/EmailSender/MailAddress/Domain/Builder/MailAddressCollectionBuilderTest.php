<?php

namespace Test\Unit\EmailSender\MailAddress\Domain\Builder;

use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\MailAddress\Application\Collection\MailAddressCollection;
use EmailSender\MailAddress\Domain\Builder\MailAddressBuilder;
use EmailSender\MailAddress\Domain\Builder\MailAddressCollectionBuilder;
use PHPUnit\Framework\TestCase;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;

class MailAddressCollectionBuilderTest extends TestCase
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
     * @return \EmailSender\MailAddress\Domain\Builder\MailAddressBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getMailAddressBuilderMock()
    {
        /** @var MailAddressBuilder|\PHPUnit_Framework_MockObject_MockObject $mailAddressBuilderMock */
        $mailAddressBuilderMock = $this->getMockBuilder(MailAddressBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $mailAddressBuilderMock;
    }

    /**
     * Test __construct method.
     */
    public function testConstruct()
    {
        $mailAddressCollectionBuilder = new MailAddressCollectionBuilder($this->getMailAddressBuilderMock());

        $this->assertInstanceOf(MailAddressCollectionBuilder::class, $mailAddressCollectionBuilder);
    }

    /**
     * Test buildMailAddressCollectionFromString with valid values.
     *
     * @param string                                                                $mailAddressCollectionString
     *
     * @dataProvider providerForTestBuildMailAddressCollectionFromStringWithValidValues
     */
    public function testBuildMailAddressCollectionFromStringWithValidValues(
        string $mailAddressCollectionString
    ) {
        $mailAddressCollectionBuilder = new MailAddressCollectionBuilder($this->getMailAddressBuilderMock());

        $this->assertInstanceOf(
            MailAddressCollection::class,
            $mailAddressCollectionBuilder->buildMailAddressCollectionFromString($mailAddressCollectionString)
        );
    }

    /**
     * Test buildMailAddressCollectionFromString with Exception (TypeError etc...).
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage  Error message
     */
    public function testBuildMailAddressCollectionFromStringWithTypeError()
    {
        $mailAddressBuilder = $this->getMailAddressBuilderMock();

        $mailAddressBuilder->expects($this->once())
            ->method('buildMailAddressFromString')
            ->willThrowException(new ValueObjectException('Error message'));

        $mailAddressCollectionBuilder = new MailAddressCollectionBuilder($mailAddressBuilder);

        $mailAddressCollectionBuilder->buildMailAddressCollectionFromString('test');

        $this->fail();
    }

    /**
     * Provider for testBuildMailAddressCollectionFromStringWithValidValues.
     *
     * @return array
     */
    public function providerForTestBuildMailAddressCollectionFromStringWithValidValues()
    {
        return [
            [
                'test string',
            ],
            [
                'test;string',
            ],
        ];
    }
}
