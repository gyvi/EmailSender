<?php

namespace Test\Unit\EmailSender\EmailLog\Domain\Service;

use EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryWriterInterface;
use EmailSender\EmailLog\Domain\Factory\EmailLogFactory;
use EmailSender\EmailLog\Domain\Service\AddEmailLogService;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class AddEmailLogServiceTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class AddEmailLogServiceTest extends TestCase
{
    /**
     * Test add method.
     */
    public function testAdd()
    {
        $email         = (new Mockery($this))->getEmailMock();
        $composedEmail = (new Mockery($this))->getComposedEmailMock();
        $emailLog      = (new Mockery($this))->getEmailLogMock();
        $emailLogId    = (new Mockery($this))->getUnSignedIntegerMock(1);

        $emailLog->expects($this->once())
            ->method('setEmailLogId')
            ->willReturn(null);

        /** @var \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryWriterInterface|\PHPUnit_Framework_MockObject_MockObject $repositoryWriter */
        $repositoryWriter = $this->getMockBuilder(EmailLogRepositoryWriterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repositoryWriter->expects($this->once())
            ->method('add')
            ->willReturn($emailLogId);

        /** @var \EmailSender\EmailLog\Domain\Factory\EmailLogFactory|\PHPUnit_Framework_MockObject_MockObject $emailLogFactory */
        $emailLogFactory = $this->getMockBuilder(EmailLogFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $emailLogFactory->expects($this->once())
            ->method('create')
            ->willReturn($emailLog);

        $addEmailLogService = new AddEmailLogService($repositoryWriter, $emailLogFactory);

        $this->assertEquals($emailLog, $addEmailLogService->add($email, $composedEmail));
    }
}
