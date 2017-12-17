<?php

namespace Test\Unit\EmailSender\EmailQueue\Domain\Service;

use EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory;
use EmailSender\EmailQueue\Domain\Service\GetEmailQueueService;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class GetEmailQueueServiceTest
 *
 * @package Test\Unit\EmailSender\EmailQueue
 */
class GetEmailQueueServiceTest extends TestCase
{
    /**
     * Test get method.
     */
    public function testGet()
    {
        $emailQueue = (new Mockery($this))->getEmailQueueMock();

        /** @var \EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory|\PHPUnit_Framework_MockObject_MockObject $emailQueueFactory */
        $emailQueueFactory = $this->getMockBuilder(EmailQueueFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $emailQueueFactory->expects($this->once())
            ->method('createFromArray')
            ->willReturn($emailQueue);

        $getEmailQueueService = new GetEmailQueueService($emailQueueFactory);

        $this->assertEquals($emailQueue, $getEmailQueueService->get([]));
    }
}
