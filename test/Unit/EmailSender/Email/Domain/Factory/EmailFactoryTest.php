<?php

namespace Test\Unit\EmailSender\Email\Domain\Factory;

use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\Core\ValueObject\Subject;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\Email\Domain\Factory\EmailFactory;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;
use EmailSender\Email\Application\Catalog\EmailPropertyNameList;

class EmailFactoryTest extends TestCase
{
    /**
     * @param bool $exception
     *
     * @return \EmailSender\Core\Factory\EmailAddressFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getEmailAddressFactory(bool $exception = false)
    {
        /** @var \EmailSender\Core\Factory\EmailAddressFactory|\PHPUnit_Framework_MockObject_MockObject $emailAddressFactory */
        $emailAddressFactory = $this->getMockBuilder(EmailAddressFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        if ($exception) {
            $emailAddressFactory->expects($this->any())
                ->method('create')
                ->willThrowException(new ValueObjectException('errorMessage'));

            return $emailAddressFactory;
        }

        $emailAddressFactory->expects($this->any())
            ->method('create')
            ->willReturn((new Mockery($this))->getEmailAddressMock());

        return $emailAddressFactory;
    }

    /**
     * @param bool $isEmpty
     * @param bool $exception
     *
     * @return \EmailSender\Core\Factory\EmailAddressCollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getEmailAddressCollectionFactory(bool $isEmpty = false, bool $exception = false)
    {
        /** @var \EmailSender\Core\Factory\EmailAddressCollectionFactory|\PHPUnit_Framework_MockObject_MockObject $emailAddressCollectionFactory */
        $emailAddressCollectionFactory = $this->getMockBuilder(EmailAddressCollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        if ($exception) {
            $emailAddressCollectionFactory->expects($this->any())
                ->method('createFromString')
                ->willThrowException(new ValueObjectException('errorMessage'));

            return $emailAddressCollectionFactory;
        }

        $emailAddressCollection = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $emailAddressCollection->expects($this->any())
            ->method('isEmpty')
            ->willReturn($isEmpty);

        $emailAddressCollectionFactory->expects($this->any())
            ->method('createFromString')
            ->willReturn($emailAddressCollection);

        return $emailAddressCollectionFactory;
    }

    /**
     * Test create method with valid values and with replyTo.
     */
    public function testCreateWithValidValues()
    {
        $request = $this->getValidRequestArray();

        $emailFactory = new EmailFactory(
            $this->getEmailAddressFactory(),
            $this->getEmailAddressCollectionFactory()
        );

        $email = $emailFactory->create($request);

        $this->assertInstanceOf(Email::class, $email);
    }

    /**
     * Test create method with valid values and without replyTo.
     */
    public function testCreateWithValidValuesWithoutReplyTo()
    {
        $request = $this->getValidRequestArray();

        $request[EmailPropertyNameList::REPLY_TO] = null;

        $emailFactory = new EmailFactory(
            $this->getEmailAddressFactory(),
            $this->getEmailAddressCollectionFactory()
        );

        $email = $emailFactory->create($request);

        $this->assertInstanceOf(Email::class, $email);

        unset($request[EmailPropertyNameList::REPLY_TO]);
        $email = $emailFactory->create($request);

        $this->assertInstanceOf(Email::class, $email);
    }

    /**
     * Test create method with invalid from value.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidFrom()
    {
        $request = $this->getValidRequestArray();

        $emailFactory = new EmailFactory(
            $this->getEmailAddressFactory(true),
            $this->getEmailAddressCollectionFactory()
        );

        $emailFactory->create($request);
    }

    /**
     * Test create method with invalid to value.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidTo()
    {
        $request = $this->getValidRequestArray();

        $emailFactory = new EmailFactory(
            $this->getEmailAddressFactory(),
            $this->getEmailAddressCollectionFactory(false, true)
        );

        $emailFactory->create($request);
    }

    /**
     * Test create method with empty to value.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithEmptyTo()
    {
        $request = $this->getValidRequestArray();

        $emailFactory = new EmailFactory(
            $this->getEmailAddressFactory(),
            $this->getEmailAddressCollectionFactory(true)
        );

        $emailFactory->create($request);
    }

    /**
     * Test create method with invalid cc value.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidCc()
    {
        $request = $this->getValidRequestArray();

        $emailAddressCollectionFactory = $this->getEmailAddressCollectionFactory();

        $emailAddressCollectionFactory->expects($this->at(0))
            ->method('createFromString')
            ->willReturn((new Mockery($this))->getEmailAddressCollectionMock([]));

        $emailAddressCollectionFactory->expects($this->at(1))
            ->method('createFromString')
            ->willThrowException(new ValueObjectException('errorMessage'));

        $emailFactory = new EmailFactory(
            $this->getEmailAddressFactory(),
            $emailAddressCollectionFactory
        );

        $emailFactory->create($request);
    }

    /**
     * Test create method with invalid bcc value.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidBcc()
    {
        $request = $this->getValidRequestArray();

        $emailAddressCollectionFactory = $this->getEmailAddressCollectionFactory();

        $emailAddressCollectionFactory->expects($this->at(0))
            ->method('createFromString')
            ->willReturn((new Mockery($this))->getEmailAddressCollectionMock([]));

        $emailAddressCollectionFactory->expects($this->at(1))
            ->method('createFromString')
            ->willReturn((new Mockery($this))->getEmailAddressCollectionMock([]));

        $emailAddressCollectionFactory->expects($this->at(2))
            ->method('createFromString')
            ->willThrowException(new ValueObjectException('errorMessage'));

        $emailFactory = new EmailFactory(
            $this->getEmailAddressFactory(),
            $emailAddressCollectionFactory
        );

        $emailFactory->create($request);
    }

    /**
     * Test create method with invalid subject value.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidSubject()
    {
        $request = $this->getValidRequestArray();
        $request[EmailPropertyNameList::SUBJECT] = str_pad('', Subject::MAX_LENGTH + 1, 'x');

        $emailFactory = new EmailFactory(
            $this->getEmailAddressFactory(),
            $this->getEmailAddressCollectionFactory()
        );

        $emailFactory->create($request);
    }

    /**
     * Test create method with invalid body value.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidBody()
    {
        $request = $this->getValidRequestArray();
        $request[EmailPropertyNameList::BODY] = '';

        $emailFactory = new EmailFactory(
            $this->getEmailAddressFactory(),
            $this->getEmailAddressCollectionFactory()
        );

        $emailFactory->create($request);
    }

    /**
     * Test create method with invalid replyTo value.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidReplyTo()
    {
        $request = $this->getValidRequestArray();

        $emailAddressFactory = $this->getEmailAddressFactory();

        $emailAddressFactory->expects($this->at(0))
            ->method('create')
            ->willReturn((new Mockery($this))->getEmailAddressMock());

        $emailAddressFactory->expects($this->at(1))
            ->method('create')
            ->willThrowException(new ValueObjectException('errorMessage'));

        $emailFactory = new EmailFactory(
            $emailAddressFactory,
            $this->getEmailAddressCollectionFactory()
        );

        $emailFactory->create($request);
    }

    /**
     * Test create method with invalid delay value.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidDelay()
    {
        $request = $this->getValidRequestArray();
        $request[EmailPropertyNameList::DELAY] = '-1';

        $emailFactory = new EmailFactory(
            $this->getEmailAddressFactory(),
            $this->getEmailAddressCollectionFactory()
        );

        $emailFactory->create($request);
    }

    /**
     * @return array
     */
    private function getValidRequestArray(): array
    {
        return [
            EmailPropertyNameList::FROM     => 'emailAddress',
            EmailPropertyNameList::TO       => 'to',
            EmailPropertyNameList::CC       => 'cc',
            EmailPropertyNameList::BCC      => 'bcc',
            EmailPropertyNameList::SUBJECT  => 'subject',
            EmailPropertyNameList::BODY     => 'body',
            EmailPropertyNameList::REPLY_TO => 'emailAddress',
            EmailPropertyNameList::DELAY    => 'emailAddress',
        ];
    }
}
