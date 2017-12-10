<?php

namespace Test\Unit\EmailSender\ComposedEmail\Domain\Service;

use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface;
use EmailSender\ComposedEmail\Domain\Contract\SMTPSenderInterface;
use EmailSender\ComposedEmail\Domain\Service\SendComposedEmailByIdService;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class SendComposedEmailByIdServiceTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class SendComposedEmailByIdServiceTest extends TestCase
{
    /**
     * Test send method.
     */
    public function testSend()
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

        $sendComposedEmailByIdService = new SendComposedEmailByIdService($smtpSenderInterface, $repositoryReader);

        $sendComposedEmailByIdService->send($composedEmailId);
    }
}
