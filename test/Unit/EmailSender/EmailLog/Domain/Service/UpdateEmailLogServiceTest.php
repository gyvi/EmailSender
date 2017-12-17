<?php

namespace Test\Unit\EmailSender\EmailLog\Domain\Service;

use EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryWriterInterface;
use EmailSender\EmailLog\Domain\Service\UpdateEmailLogService;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class UpdateEmailLogServiceTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class UpdateEmailLogServiceTest extends TestCase
{
    /**
     * Test setStatus method.
     */
    public function testSetStatus()
    {
        $emailLogId   = (new Mockery($this))->getUnSignedIntegerMock(1);
        $emailStatus  = (new Mockery($this))->getEmailStatusMock(2);
        $errorMessage = (new Mockery($this))->getStringLiteralMock('');

        /** @var \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryWriterInterface|\PHPUnit_Framework_MockObject_MockObject $repositoryWriter */
        $repositoryWriter = $this->getMockBuilder(EmailLogRepositoryWriterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repositoryWriter->expects($this->once())
            ->method('setStatus')
            ->willReturn($emailLogId);

        $updateEmailLogService = new UpdateEmailLogService($repositoryWriter);

        $updateEmailLogService->setStatus($emailLogId, $emailStatus, $errorMessage);
    }
}
