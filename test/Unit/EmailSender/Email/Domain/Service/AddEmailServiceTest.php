<?php

namespace Test\Unit\EmailSender\Email\Domain\Service;

use EmailSender\ComposedEmail\Application\Contract\ComposedEmailServiceInterface;
use EmailSender\ComposedEmail\Application\Exception\ComposedEmailException;
use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\Email\Domain\Factory\EmailFactory;
use EmailSender\Email\Domain\Service\AddEmailService;
use EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface;
use EmailSender\EmailQueue\Application\Contract\EmailQueueServiceInterface;
use EmailSender\EmailQueue\Application\Exception\EmailQueueException;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class AddEmailServiceTest
 *
 * @package Test\Unit\EmailSender\Email
 */
class AddEmailServiceTest extends TestCase
{
    /**
     * @var \EmailSender\ComposedEmail\Application\Contract\ComposedEmailServiceInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $composedEmailService;

    /**
     * @var \EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $emailLogService;

    /**
     * @var \EmailSender\EmailQueue\Application\Contract\EmailQueueServiceInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $emailQueueService;

    /**
     * @var \EmailSender\Email\Domain\Factory\EmailFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $emailFactory;

    /**
     * Test's setup.
     */
    public function setUp()
    {
        $this->composedEmailService = $this->getMockBuilder(ComposedEmailServiceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->emailLogService = $this->getMockBuilder(EmailLogServiceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->emailQueueService = $this->getMockBuilder(EmailQueueServiceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->emailFactory = $this->getMockBuilder(EmailFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $composedEmail = (new Mockery($this))->getComposedEmailMock();
        $emailLog      = (new Mockery($this))->getEmailLogMock();

        $emailLog->expects($this->once())
            ->method('getEmailLogId')
            ->willReturn((new Mockery($this))->getUnSignedIntegerMock(1));

        $this->composedEmailService->expects($this->once())
            ->method('add')
            ->willReturn($composedEmail);

        $this->emailLogService->expects($this->once())
            ->method('add')
            ->willReturn($emailLog);

        $this->emailLogService->expects($this->once())
            ->method('setStatus')
            ->willReturn(null);
    }

    /**
     * Test add method with delay = 0.
     */
    public function testAddWithoutDelay()
    {
        $email       = (new Mockery($this))->getEmailMock(null, null, null, null, null, null, null, 0);
        $emailStatus = (new Mockery($this))->getEmailStatusMock(EmailStatusList::SENT);

        $this->emailFactory->expects($this->once())
            ->method('create')
            ->willReturn($email);

        $this->composedEmailService->expects($this->once())
            ->method('send')
            ->willReturn($emailStatus);

        $email->expects($this->once())
            ->method('setEmailStatus')
            ->with(
                $this->equalTo($emailStatus)
            )->willReturn(null);

        $addEmailService = new AddEmailService(
            $this->composedEmailService,
            $this->emailLogService,
            $this->emailQueueService,
            $this->emailFactory
        );

        $this->assertEquals($email, $addEmailService->add([]));
    }

    /**
     * Test add method with delay = 0 and with ComposedEmailException.
     *
     * @expectedException \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     * @expectedExceptionMessage exceptionMessage
     */
    public function testAddWithoutDelayWithComposedEmailException()
    {
        $expected = (new Mockery($this))->getEmailStatusMock(EmailStatusList::SENT);
        $email    = (new Mockery($this))->getEmailMock(null, null, null, null, null, null, null, 0);

        $this->emailFactory->expects($this->once())
            ->method('create')
            ->willReturn($email);

        $this->composedEmailService->expects($this->once())
            ->method('send')
            ->willThrowException(new ComposedEmailException('exceptionMessage'));

        $addEmailService = new AddEmailService(
            $this->composedEmailService,
            $this->emailLogService,
            $this->emailQueueService,
            $this->emailFactory
        );

        $this->assertEquals($expected, $addEmailService->add([]));
    }

    /**
     * Test add method with delay > 0.
     */
    public function testAddWithDelay()
    {
        $emailStatus = (new Mockery($this))->getEmailStatusMock(EmailStatusList::SENT);
        $email       = (new Mockery($this))->getEmailMock(null, null, null, null, null, null, null, 1);

        $this->emailFactory->expects($this->once())
            ->method('create')
            ->willReturn($email);

        $this->emailQueueService->expects($this->once())
            ->method('add')
            ->willReturn($emailStatus);

        $email->expects($this->once())
            ->method('setEmailStatus')
            ->with(
                $this->equalTo($emailStatus)
            )->willReturn(null);

        $addEmailService = new AddEmailService(
            $this->composedEmailService,
            $this->emailLogService,
            $this->emailQueueService,
            $this->emailFactory
        );

        $this->assertEquals($email, $addEmailService->add([]));
    }

    /**
     * Test add method with delay > 0 and with EmailQueueException.
     *
     * @expectedException \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     * @expectedExceptionMessage exceptionMessage
     */
    public function testAddWithDelayWithEmailQueueException()
    {
        $expected = (new Mockery($this))->getEmailStatusMock(EmailStatusList::SENT);
        $email    = (new Mockery($this))->getEmailMock(null, null, null, null, null, null, null, 1);

        $this->emailFactory->expects($this->once())
            ->method('create')
            ->willReturn($email);

        $this->emailQueueService->expects($this->once())
            ->method('add')
            ->willThrowException(new EmailQueueException('exceptionMessage'));

        $addEmailService = new AddEmailService(
            $this->composedEmailService,
            $this->emailLogService,
            $this->emailQueueService,
            $this->emailFactory
        );

        $this->assertEquals($expected, $addEmailService->add([]));
    }
}
