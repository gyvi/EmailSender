<?php

namespace Test\Unit\EmailSender\ComposedEmail\Domain\Factory;

use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\ComposedEmail\Domain\Contract\EmailComposerInterface;
use EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory;
use EmailSender\ComposedEmail\Infrastructure\Persistence\ComposedEmailRepositoryFieldList;
use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class ComposedEmailFactoryTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class ComposedEmailFactoryTest extends TestCase
{
    /**
     * Test create method.
     */
    public function testCreate()
    {
        $recipients         = (new Mockery($this))->getRecipientsMock();
        $emailStringLiteral = (new Mockery($this))->getStringLiteralMock('composed email string');
        $email              = (new Mockery($this))->getEmailMock(
            [
                EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                EmailAddressPropertyNameList::NAME    => null,
            ]
        );

        /** @var \EmailSender\Core\Factory\RecipientsFactory|\PHPUnit_Framework_MockObject_MockObject $recipientsFactory */
        $recipientsFactory = $this->getMockBuilder(RecipientsFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $recipientsFactory->expects($this->once())
            ->method('create')
            ->willReturn($recipients);

        /** @var \EmailSender\ComposedEmail\Domain\Contract\EmailComposerInterface|\PHPUnit_Framework_MockObject_MockObject $emailComposer */
        $emailComposer = $this->getMockBuilder(EmailComposerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $emailComposer->expects($this->once())
            ->method('compose')
            ->willReturn($emailStringLiteral);

        /** @var \EmailSender\Core\Factory\EmailAddressFactory|\PHPUnit_Framework_MockObject_MockObject $emailAddressFactory */
        $emailAddressFactory = $this->getMockBuilder(EmailAddressFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $composedEmailFactory = new ComposedEmailFactory($emailComposer, $recipientsFactory, $emailAddressFactory);

        $this->assertInstanceOf(ComposedEmail::class, $composedEmailFactory->create($email));
    }

    /**
     * Test createFromArray method.
     */
    public function testCreateFromArray()
    {
        $composedEmailArray = [
            ComposedEmailRepositoryFieldList::RECIPIENTS        => json_encode([]),
            ComposedEmailRepositoryFieldList::FROM              => 'emailaddress',
            ComposedEmailRepositoryFieldList::EMAIL             => 'composed email string',
            ComposedEmailRepositoryFieldList::COMPOSED_EMAIL_ID => 1,
        ];
        $recipients         = (new Mockery($this))->getRecipientsMock();
        $from               = (new Mockery($this))->getEmailAddressMock('emailaddress');

        /** @var \EmailSender\Core\Factory\RecipientsFactory|\PHPUnit_Framework_MockObject_MockObject $recipientsFactory */
        $recipientsFactory = $this->getMockBuilder(RecipientsFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $recipientsFactory->expects($this->once())
            ->method('createFromArray')
            ->willReturn($recipients);

        /** @var \EmailSender\ComposedEmail\Domain\Contract\EmailComposerInterface|\PHPUnit_Framework_MockObject_MockObject $emailComposer */
        $emailComposer = $this->getMockBuilder(EmailComposerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \EmailSender\Core\Factory\EmailAddressFactory|\PHPUnit_Framework_MockObject_MockObject $emailAddressFactory */
        $emailAddressFactory = $this->getMockBuilder(EmailAddressFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $emailAddressFactory->expects($this->once())
            ->method('create')
            ->willReturn($from);

        $composedEmailFactory = new ComposedEmailFactory($emailComposer, $recipientsFactory, $emailAddressFactory);

        $composedEmail = $composedEmailFactory->createFromArray($composedEmailArray);

        $this->assertInstanceOf(ComposedEmail::class, $composedEmail);
        $this->assertInstanceOf(UnsignedInteger::class, $composedEmail->getComposedEmailId());
    }
}
