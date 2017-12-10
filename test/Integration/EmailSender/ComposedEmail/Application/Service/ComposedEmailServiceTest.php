<?php

namespace Test\Integration\EmailSender\ComposedEmail\Application\Service;

use EmailSender\ComposedEmail\Application\Catalog\ComposedEmailPropertyNameList;
use EmailSender\ComposedEmail\Application\Service\ComposedEmailService;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\Core\Catalog\RecipientsPropertyNameList;
use EmailSender\Core\ValueObject\EmailStatus;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class ComposedEmailServiceTest
 *
 * @package Test\Integration\EmailSender\ComposedEmail
 */
class ComposedEmailServiceTest extends TestCase
{
    /**
     * Test add method with valid values.
     */
    public function testAdd()
    {
        $from    = [
            EmailAddressPropertyNameList::ADDRESS => 'emailAddress',
            EmailAddressPropertyNameList::NAME => null
        ];
        $to      = [[
            EmailAddressPropertyNameList::ADDRESS => 'emailAddress',
            EmailAddressPropertyNameList::NAME => null
        ]];
        $cc      = [];
        $bcc     = [];
        $subject = 'subject';
        $body    = 'body';
        $replyTo = null;
        $delay   = 0;
        $composedEmailId   = 1;

        $logger                        = (new Mockery($this))->getLoggerMock();
        $composedEmailReaderService    = (new Mockery($this))->getRepositoryServiceMock();
        $composedEmailRepositoryWriter = (new Mockery($this))->getRepositoryMock(true, null, null, $composedEmailId);
        $composedEmailWriterService    = (new Mockery($this))->getRepositoryServiceMock($composedEmailRepositoryWriter);
        $smtpService                   = (new Mockery($this))->getSMTPServiceMock();

        $email = (new Mockery($this))->getEmailMock($from, $to, $cc, $bcc, $subject, $body, $replyTo, $delay);

        $composedEmailService = new ComposedEmailService(
            $logger,
            $composedEmailReaderService,
            $composedEmailWriterService,
            $smtpService
        );

        $composedEmail = $composedEmailService->add($email);

        $this->assertInstanceOf(ComposedEmail::class, $composedEmail);
        $this->assertEquals($composedEmailId, $composedEmail->getComposedEmailId()->getValue());
    }

    /**
     * Test add method with exception.
     *
     * @expectedException \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     * @expectedExceptionMessage Something went wrong when try to store the composed email.
     */
    public function testAddWithException()
    {
        $logger                        = (new Mockery($this))->getLoggerMock();
        $composedEmailReaderService    = (new Mockery($this))->getRepositoryServiceMock();
        $composedEmailWriterService    = (new Mockery($this))->getRepositoryServiceMock();
        $smtpService                   = (new Mockery($this))->getSMTPServiceMock();

        $email = (new Mockery($this))->getEmailMock();

        $logger->expects($this->once())
            ->method('alert')
            ->willReturn(null);

        $composedEmailService = new ComposedEmailService(
            $logger,
            $composedEmailReaderService,
            $composedEmailWriterService,
            $smtpService
        );

        $composedEmailService->add($email);
    }

    /**
     * Test get method with valid values.
     */
    public function testGetWithValidValues()
    {
        $emailAddress    = 'test@test.com';
        $composedEmailId = 1;
        $recipients      = [
            RecipientsPropertyNameList::TO => [
                [
                    EmailAddressPropertyNameList::ADDRESS => $emailAddress,
                    EmailAddressPropertyNameList::NAME    => null,
                ]
            ],
            RecipientsPropertyNameList::CC => [],
            RecipientsPropertyNameList::BCC => [],
        ];
        $composedEmailData = 'emailData';

        $logger                        = (new Mockery($this))->getLoggerMock();
        $composedEmailRepositoryReader = (new Mockery($this))->getRepositoryMock(
            true,
            [
                ComposedEmailPropertyNameList::COMPOSED_EMAIL_ID => $composedEmailId,
                ComposedEmailPropertyNameList::FROM              => $emailAddress,
                ComposedEmailPropertyNameList::RECIPIENTS        => json_encode($recipients),
                ComposedEmailPropertyNameList::EMAIL             => $composedEmailData,
            ]
        );
        $composedEmailReaderService    = (new Mockery($this))->getRepositoryServiceMock($composedEmailRepositoryReader);
        $composedEmailWriterService    = (new Mockery($this))->getRepositoryServiceMock();
        $smtpService                   = (new Mockery($this))->getSMTPServiceMock();

        $composedEmailIdMock = (new Mockery($this))->getUnSignedIntegerMock(1);

        $composedEmailService = new ComposedEmailService(
            $logger,
            $composedEmailReaderService,
            $composedEmailWriterService,
            $smtpService
        );

        $composedEmail = $composedEmailService->get($composedEmailIdMock);

        $this->assertInstanceOf(ComposedEmail::class, $composedEmail);
        $this->assertEquals($composedEmailId, $composedEmail->getComposedEmailId()->getValue());
    }

    /**
     * Test get method with exception.
     *
     * @expectedException \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     * @expectedExceptionMessage Something went wrong when try to read the composed email.
     */
    public function testGetWithException()
    {
        $logger                        = (new Mockery($this))->getLoggerMock();
        $composedEmailRepositoryReader = (new Mockery($this))->getRepositoryMock(false);
        $composedEmailReaderService    = (new Mockery($this))->getRepositoryServiceMock($composedEmailRepositoryReader);
        $composedEmailWriterService    = (new Mockery($this))->getRepositoryServiceMock();
        $smtpService                   = (new Mockery($this))->getSMTPServiceMock();

        $composedEmailIdMock = (new Mockery($this))->getUnSignedIntegerMock(1);

        $logger->expects($this->once())
            ->method('alert')
            ->willReturn(null);

        $composedEmailService = new ComposedEmailService(
            $logger,
            $composedEmailReaderService,
            $composedEmailWriterService,
            $smtpService
        );

        $composedEmailService->get($composedEmailIdMock);
    }

    /**
     * Test send method with valid values.
     */
    public function testSendWithValidValues()
    {
        $logger                     = (new Mockery($this))->getLoggerMock();
        $composedEmailReaderService = (new Mockery($this))->getRepositoryServiceMock();
        $composedEmailWriterService = (new Mockery($this))->getRepositoryServiceMock();

        $smtp = (new Mockery($this))->getSMTPMock();
        $smtp->expects($this->once())
            ->method('mail')
            ->willReturn(true);

        $smtp->expects($this->any())
            ->method('recipient')
            ->willReturn(true);

        $smtp->expects($this->once())
            ->method('data')
            ->willReturn(true);

        $smtpService = (new Mockery($this))->getSMTPServiceMock($smtp);

        $composedEmail = (new Mockery($this))->getComposedEmailMock();

        $composedEmailService = new ComposedEmailService(
            $logger,
            $composedEmailReaderService,
            $composedEmailWriterService,
            $smtpService
        );

        $emailStatus = $composedEmailService->send($composedEmail);

        $this->assertInstanceOf(EmailStatus::class, $emailStatus);
        $this->assertEquals(EmailStatusList::STATUS_SENT, $emailStatus->getValue());
    }

    /**
     * Test send method with valid values.
     *
     * @expectedException \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     * @expectedExceptionMessage Something went wrong when try to send the composed email.
     */
    public function testSendWithException()
    {
        $logger                     = (new Mockery($this))->getLoggerMock();
        $composedEmailReaderService = (new Mockery($this))->getRepositoryServiceMock();
        $composedEmailWriterService = (new Mockery($this))->getRepositoryServiceMock();

        $smtp = (new Mockery($this))->getSMTPMock();
        $smtp->expects($this->once())
            ->method('mail')
            ->willReturn(false);

        $smtpService = (new Mockery($this))->getSMTPServiceMock($smtp);

        $logger->expects($this->once())
            ->method('alert')
            ->willReturn(null);

        $composedEmail = (new Mockery($this))->getComposedEmailMock();

        $composedEmailService = new ComposedEmailService(
            $logger,
            $composedEmailReaderService,
            $composedEmailWriterService,
            $smtpService
        );

        $composedEmailService->send($composedEmail);
    }

    /**
     * Test sendById with valid values.
     */
    public function testSendByIdWithValidValues()
    {
        $emailAddress    = 'test@test.com';
        $composedEmailId = 1;
        $recipients      = [
            RecipientsPropertyNameList::TO => [
                [
                    EmailAddressPropertyNameList::ADDRESS => $emailAddress,
                    EmailAddressPropertyNameList::NAME    => null,
                ]
            ],
            RecipientsPropertyNameList::CC => [],
            RecipientsPropertyNameList::BCC => [],
        ];
        $composedEmailData = 'emailData';

        $logger                        = (new Mockery($this))->getLoggerMock();
        $composedEmailRepositoryReader = (new Mockery($this))->getRepositoryMock(
            true,
            [
                ComposedEmailPropertyNameList::COMPOSED_EMAIL_ID => $composedEmailId,
                ComposedEmailPropertyNameList::FROM              => $emailAddress,
                ComposedEmailPropertyNameList::RECIPIENTS        => json_encode($recipients),
                ComposedEmailPropertyNameList::EMAIL             => $composedEmailData,
            ]
        );
        $composedEmailReaderService = (new Mockery($this))->getRepositoryServiceMock($composedEmailRepositoryReader);
        $composedEmailWriterService = (new Mockery($this))->getRepositoryServiceMock();

        $smtp = (new Mockery($this))->getSMTPMock();
        $smtp->expects($this->once())
            ->method('mail')
            ->willReturn(true);

        $smtp->expects($this->any())
            ->method('recipient')
            ->willReturn(true);

        $smtp->expects($this->once())
            ->method('data')
            ->willReturn(true);

        $smtpService = (new Mockery($this))->getSMTPServiceMock($smtp);

        $composedEmailIdMock = (new Mockery($this))->getUnSignedIntegerMock($composedEmailId);

        $composedEmailService = new ComposedEmailService(
            $logger,
            $composedEmailReaderService,
            $composedEmailWriterService,
            $smtpService
        );

        $emailStatus = $composedEmailService->sendById($composedEmailIdMock);

        $this->assertInstanceOf(EmailStatus::class, $emailStatus);
        $this->assertEquals(EmailStatusList::STATUS_SENT, $emailStatus->getValue());
    }

    /**
     * Test sendById method with exception.
     *
     * @expectedException \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     * @expectedExceptionMessage Something went wrong when try to send the composed email.
     */
    public function testSendByIdWithException()
    {
        $logger                        = (new Mockery($this))->getLoggerMock();
        $composedEmailRepositoryReader = (new Mockery($this))->getRepositoryMock(false);
        $composedEmailReaderService    = (new Mockery($this))->getRepositoryServiceMock($composedEmailRepositoryReader);
        $composedEmailWriterService    = (new Mockery($this))->getRepositoryServiceMock();
        $smtpService                   = (new Mockery($this))->getSMTPServiceMock();

        $composedEmailIdMock = (new Mockery($this))->getUnSignedIntegerMock(1);

        $logger->expects($this->once())
            ->method('alert')
            ->willReturn(null);

        $composedEmailService = new ComposedEmailService(
            $logger,
            $composedEmailReaderService,
            $composedEmailWriterService,
            $smtpService
        );

        $composedEmailService->sendById($composedEmailIdMock);
    }
}
