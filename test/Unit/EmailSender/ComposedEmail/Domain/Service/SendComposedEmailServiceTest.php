<?php

namespace Test\Unit\EmailSender\ComposedEmail\Domain\Service;

use EmailSender\ComposedEmail\Domain\Contract\SMTPSenderInterface;
use EmailSender\ComposedEmail\Domain\Service\SendComposedEmailService;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class SendComposedEmailServiceTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class SendComposedEmailServiceTest extends TestCase
{
    /**
     * Test send method.
     */
    public function testSend()
    {
        $composedEmail = (new Mockery($this))->getComposedEmailMock();

        /** @var \EmailSender\ComposedEmail\Domain\Contract\SMTPSenderInterface|\PHPUnit_Framework_MockObject_MockObject $smtpSenderInterface */
        $smtpSenderInterface = $this->getMockBuilder(SMTPSenderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $smtpSenderInterface->expects($this->once())
            ->method('send')
            ->willReturn(null);

        $sendComposedEmailService = new SendComposedEmailService($smtpSenderInterface);

        $sendComposedEmailService->send($composedEmail);
    }
}
