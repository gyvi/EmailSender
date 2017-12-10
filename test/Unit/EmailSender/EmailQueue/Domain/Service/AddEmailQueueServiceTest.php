<?php

namespace Test\Unit\EmailSender\EmailQueue\Domain\Service;

use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\EmailQueue\Domain\Contract\EmailQueueRepositoryWriterInterface;
use EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory;
use EmailSender\EmailQueue\Domain\Service\AddEmailQueueService;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class AddEmailQueueServiceTest
 *
 * @package Test\Unit\EmailSender\EmailQueue
 */
class AddEmailQueueServiceTest extends TestCase
{
    /**
     * Test add method.
     */
    public function testAdd()
    {
        $expected   = (new Mockery($this))->getEmailStatusMock(EmailStatusList::STATUS_QUEUED);
        $emailQueue = (new Mockery($this))->getEmailQueueMock();
        $emailLog   = (new Mockery($this))->getEmailLogMock();

        /** @var \EmailSender\EmailQueue\Domain\Contract\EmailQueueRepositoryWriterInterface|\PHPUnit_Framework_MockObject_MockObject $queueWriter */
        $queueWriter = $this->getMockBuilder(EmailQueueRepositoryWriterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queueWriter->expects($this->once())
            ->method('add')
            ->willReturn($expected);

        /** @var \EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory|\PHPUnit_Framework_MockObject_MockObject $emailQueueFactory */
        $emailQueueFactory = $this->getMockBuilder(EmailQueueFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $emailQueueFactory->expects($this->once())
            ->method('create')
            ->willReturn($emailQueue);

        $addEmailQueueService = new AddEmailQueueService($queueWriter, $emailQueueFactory);

        $this->assertEquals($expected, $addEmailQueueService->add($emailLog));
    }
}
