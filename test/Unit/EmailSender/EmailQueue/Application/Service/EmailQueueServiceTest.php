<?php

namespace Test\Unit\EmailSender\EmailQueue\Application\Service;

use EmailSender\EmailQueue\Application\Service\EmailQueueService;
use PHPUnit\Framework\TestCase;
use Closure;
use Psr\Log\LoggerInterface;

/**
 * Class EmailQueueServiceTest
 *
 * @package Test\Unit\EmailSender\EmailQueue
 */
class EmailQueueServiceTest extends TestCase
{
    /**
     * Test __construct method.
     */
    public function testConstruct()
    {
        /** @var Closure $queueService */
        $queueService = function () {};

        /** @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $emailQueueService = new EmailQueueService(
            $logger,
            $queueService,
            []
        );

        $this->assertInstanceOf(EmailQueueService::class, $emailQueueService);
    }
}
