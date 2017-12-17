<?php

namespace Test\Unit\EmailSender\ComposedEmail\Infrastructure\Service;

use EmailSender\ComposedEmail\Infrastructure\Service\SMTPSender;
use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Catalog\RecipientsPropertyNameList;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class SMTPSenderTest
 *
 * @package Unit\EmailSender\ComposedEmail
 */
class SMTPSenderTest extends TestCase
{
    /**
     * Test send method with good values.
     */
    public function testSend()
    {
        $smtp     = (new Mockery($this))->getSMTPMock();

        $smtp->expects($this->any())
            ->method('recipient')
            ->willReturn(true);

        $smtp->expects($this->once())
            ->method('mail')
            ->willReturn(true);

        $smtp->expects($this->once())
            ->method('data')
            ->willReturn(true);

        $smtpService   = (new Mockery($this))->getSMTPServiceMock($smtp);

        $composedEmail = (new Mockery($this))->getComposedEmailMock(
            null,
            [
                EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                EmailAddressPropertyNameList::NAME    => null,
            ],
            [
                RecipientsPropertyNameList::TO => [
                    [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                        EmailAddressPropertyNameList::NAME    => null,
                    ],
                    [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                        EmailAddressPropertyNameList::NAME    => null,
                    ],
                ],
                RecipientsPropertyNameList::CC => [
                    [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                        EmailAddressPropertyNameList::NAME    => null,
                    ],
                    [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                        EmailAddressPropertyNameList::NAME    => null,
                    ],
                ],
                RecipientsPropertyNameList::BCC => [
                    [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                        EmailAddressPropertyNameList::NAME    => null,
                    ],
                ],
            ],
            'email body'
        );

        $smtpSender = new SMTPSender($smtpService);

        $this->assertEquals($composedEmail, $smtpSender->send($composedEmail));
    }

    /**
     * Test send method with wrong from email address value.
     *
     * @expectedException \EmailSender\ComposedEmail\Infrastructure\Service\SMTPException
     * @expectedExceptionMessage Unable to set SMTP From: emailaddress
     */
    public function testSendWithWrongFrom()
    {
        $smtp = (new Mockery($this))->getSMTPMock();

        $smtp->expects($this->once())
            ->method('mail')
            ->willReturn(false);

        $smtpService   = (new Mockery($this))->getSMTPServiceMock($smtp);

        $composedEmail = (new Mockery($this))->getComposedEmailMock(
            null,
            [
                EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                EmailAddressPropertyNameList::NAME    => null,
            ]
        );

        $smtpSender = new SMTPSender($smtpService);

        $smtpSender->send($composedEmail);
    }

    /**
     * Test send method with a wrong recipient email address value.
     *
     * @expectedException \EmailSender\ComposedEmail\Infrastructure\Service\SMTPException
     * @expectedExceptionMessage Unable to set SMTP recipient: emailaddress
     */
    public function testSendWithWrongRecipient()
    {
        $smtp = (new Mockery($this))->getSMTPMock();

        $smtp->expects($this->any())
            ->method('recipient')
            ->willReturn(false);

        $smtp->expects($this->once())
            ->method('mail')
            ->willReturn(true);

        $smtpService   = (new Mockery($this))->getSMTPServiceMock($smtp);

        $composedEmail = (new Mockery($this))->getComposedEmailMock(
            null,
            [
                EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                EmailAddressPropertyNameList::NAME    => null,
            ],
            [
                RecipientsPropertyNameList::TO => [
                    [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                        EmailAddressPropertyNameList::NAME    => null,
                    ],
                ],
                RecipientsPropertyNameList::CC => [],
                RecipientsPropertyNameList::BCC => [],
            ]
        );

        $smtpSender = new SMTPSender($smtpService);

        $smtpSender->send($composedEmail);
    }

    /**
     * Test send method with a wrong email value.
     *
     * @expectedException \EmailSender\ComposedEmail\Infrastructure\Service\SMTPException
     * @expectedExceptionMessage Unable to set SMTP body.
     */
    public function testSendWithWrongEmail()
    {
        $smtp = (new Mockery($this))->getSMTPMock();

        $smtp->expects($this->any())
            ->method('recipient')
            ->willReturn(true);

        $smtp->expects($this->once())
            ->method('mail')
            ->willReturn(true);

        $smtp->expects($this->once())
            ->method('data')
            ->willReturn(false);

        $smtpService   = (new Mockery($this))->getSMTPServiceMock($smtp);

        $composedEmail = (new Mockery($this))->getComposedEmailMock(
            null,
            [
                EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                EmailAddressPropertyNameList::NAME    => null,
            ],
            [
                RecipientsPropertyNameList::TO => [
                    [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                        EmailAddressPropertyNameList::NAME    => null,
                    ],
                ],
                RecipientsPropertyNameList::CC => [],
                RecipientsPropertyNameList::BCC => [],
            ],
            'email body'
        );

        $smtpSender = new SMTPSender($smtpService);

        $smtpSender->send($composedEmail);
    }
}
