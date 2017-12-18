<?php

namespace Test\Unit\EmailSender\EmailLog\Domain\Factory;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\Core\Catalog\RecipientsPropertyNameList;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Core\Scalar\Application\Factory\DateTimeFactory;
use EmailSender\EmailLog\Application\Catalog\EmailLogPropertyNamesList;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailLog\Domain\Factory\EmailLogFactory;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailLogFactoryTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class EmailLogFactoryTest extends TestCase
{
    /**
     * @var \EmailSender\Core\Factory\RecipientsFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $recipientsFactory;

    /**
     * @var \EmailSender\Core\Factory\EmailAddressFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $emailAddressFactory;

    /**
     * @var \EmailSender\Core\Scalar\Application\Factory\DateTimeFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dateTimeFactory;

    /**
     * Test's setup method.
     */
    public function setUp()
    {
        $this->recipientsFactory = $this->getMockBuilder(RecipientsFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->emailAddressFactory = $this->getMockBuilder(EmailAddressFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dateTimeFactory = $this->getMockBuilder(DateTimeFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Test create method.
     */
    public function testCreate()
    {
        $emailLogFactory = new EmailLogFactory(
            $this->recipientsFactory,
            $this->emailAddressFactory,
            $this->dateTimeFactory
        );

        $email = (new Mockery($this))->getEmailMock(
            [
                EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                EmailAddressPropertyNameList::NAME    => null,
            ],
            null,
            null,
            null,
            'subject',
            null,
            null,
            1
        );

        $composedEmail = (new Mockery($this))->getComposedEmailMock(
            1,
            null,
            [
                RecipientsPropertyNameList::TO  => [],
                RecipientsPropertyNameList::CC  => [],
                RecipientsPropertyNameList::BCC => [],
            ]
        );

        $this->assertInstanceOf(EmailLog::class, $emailLogFactory->create($email, $composedEmail));
    }

    /**
     * Test createFromArray method.
     *
     * @param array $emailLogArray
     *
     * @dataProvider providerForTestCreateFromArray
     */
    public function testCreateFromArray(array $emailLogArray)
    {
        $this->dateTimeFactory->expects($this->any())
            ->method('createFromDateTime')
            ->willReturn((new Mockery($this))->getDateTimeMock());

        $this->recipientsFactory->expects($this->once())
            ->method('createFromArray')
            ->willReturn((new Mockery($this))->getRecipientsMock());

        $this->emailAddressFactory->expects($this->once())
            ->method('create')
            ->willReturn((new Mockery($this))->getEmailAddressMock());

        $emailLogFactory = new EmailLogFactory(
            $this->recipientsFactory,
            $this->emailAddressFactory,
            $this->dateTimeFactory
        );

        $this->assertInstanceOf(EmailLog::class, $emailLogFactory->createFromArray($emailLogArray));
    }

    /**
     * Data provider for testCreateFromArray method.
     *
     * @return array
     */
    public function providerForTestCreateFromArray(): array
    {
        return [
            [
                [
                    EmailLogPropertyNamesList::COMPOSED_EMAIL_ID => 1,
                    EmailLogPropertyNamesList::FROM              => 'emailaddress',
                    EmailLogPropertyNamesList::RECIPIENTS        => '{"to": [{"name": "", "address": "test@test.com"}]}',
                    EmailLogPropertyNamesList::SUBJECT           => 'subject',
                    EmailLogPropertyNamesList::DELAY             => 1,
                    EmailLogPropertyNamesList::EMAIL_LOG_ID      => 1,
                    EmailLogPropertyNamesList::STATUS            => EmailStatusList::LOGGED,
                    EmailLogPropertyNamesList::LOGGED            => '2017-01-01 12:00:00',
                ],
            ],
            [
                [
                    EmailLogPropertyNamesList::COMPOSED_EMAIL_ID => 1,
                    EmailLogPropertyNamesList::FROM              => 'emailaddress',
                    EmailLogPropertyNamesList::RECIPIENTS        => '{"to": [{"name": "", "address": "test@test.com"}]}',
                    EmailLogPropertyNamesList::SUBJECT           => 'subject',
                    EmailLogPropertyNamesList::DELAY             => 1,
                    EmailLogPropertyNamesList::EMAIL_LOG_ID      => 1,
                    EmailLogPropertyNamesList::STATUS            => EmailStatusList::LOGGED,
                    EmailLogPropertyNamesList::LOGGED            => '2017-01-01 12:00:00',
                    EmailLogPropertyNamesList::QUEUED            => '2017-01-01 12:00:00',
                ],
            ],
            [
                [
                    EmailLogPropertyNamesList::COMPOSED_EMAIL_ID => 1,
                    EmailLogPropertyNamesList::FROM              => 'emailaddress',
                    EmailLogPropertyNamesList::RECIPIENTS        => '{"to": [{"name": "", "address": "test@test.com"}]}',
                    EmailLogPropertyNamesList::SUBJECT           => 'subject',
                    EmailLogPropertyNamesList::DELAY             => 1,
                    EmailLogPropertyNamesList::EMAIL_LOG_ID      => 1,
                    EmailLogPropertyNamesList::STATUS            => EmailStatusList::LOGGED,
                    EmailLogPropertyNamesList::LOGGED            => '2017-01-01 12:00:00',
                    EmailLogPropertyNamesList::SENT              => '2017-01-01 12:00:00',
                ],
            ],
            [
                [
                    EmailLogPropertyNamesList::COMPOSED_EMAIL_ID => 1,
                    EmailLogPropertyNamesList::FROM              => 'emailaddress',
                    EmailLogPropertyNamesList::RECIPIENTS        => '{"to": [{"name": "", "address": "test@test.com"}]}',
                    EmailLogPropertyNamesList::SUBJECT           => 'subject',
                    EmailLogPropertyNamesList::DELAY             => 1,
                    EmailLogPropertyNamesList::EMAIL_LOG_ID      => 1,
                    EmailLogPropertyNamesList::STATUS            => EmailStatusList::LOGGED,
                    EmailLogPropertyNamesList::LOGGED            => '2017-01-01 12:00:00',
                    EmailLogPropertyNamesList::ERROR_MESSAGE     => 'error message',
                ],
            ],
        ];
    }
}
