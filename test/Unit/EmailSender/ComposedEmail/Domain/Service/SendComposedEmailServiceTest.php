<?php

namespace Test\Unit\EmailSender\ComposedEmail\Domain\Service;

use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface;
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

        /** @var \EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface|\PHPUnit_Framework_MockObject_MockObject $repositoryReader */
        $repositoryReader = $this->getMockBuilder(ComposedEmailRepositoryReaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $sendComposedEmailService = new SendComposedEmailService($smtpSenderInterface, $repositoryReader);

        $sendComposedEmailService->send($composedEmail);
    }

    /**
     * Test sendById method.
     */
    public function testSendById()
    {
        $composedEmailId = (new Mockery($this))->getUnSignedIntegerMock(1);
        $composedEmail   = (new Mockery($this))->getComposedEmailMock();

        /** @var \EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface|\PHPUnit_Framework_MockObject_MockObject $repositoryReader */
        $repositoryReader = $this->getMockBuilder(ComposedEmailRepositoryReaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repositoryReader->expects($this->once())
            ->method('get')
            ->willReturn($composedEmail);

        /** @var \EmailSender\ComposedEmail\Domain\Contract\SMTPSenderInterface|\PHPUnit_Framework_MockObject_MockObject $smtpSenderInterface */
        $smtpSenderInterface = $this->getMockBuilder(SMTPSenderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $smtpSenderInterface->expects($this->once())
            ->method('send')
            ->willReturn(null);

        $sendComposedEmailService = new SendComposedEmailService($smtpSenderInterface, $repositoryReader);

        $sendComposedEmailService->sendById($composedEmailId);
    }
}
