<?php

namespace Test\Unit\EmailSender\MessageQueue\Application\Service;

use EmailSender\MessageQueue\Application\Service\MessageQueueService;
use PHPUnit\Framework\TestCase;
use Closure;
use Psr\Log\LoggerInterface;

/**
 * Class MessageQueueServiceTest
 *
 * @package Test\Unit\EmailSender\MessageQueue
 */
class MessageQueueServiceTest extends TestCase
{
    /**
     * Test __construct method.
     */
    public function testConstruct()
    {
        /** @var Closure $repositoryService */
        $repositoryService = function () {};

        /** @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var Closure $view */
        $view = function () {};

        $messageQueueService = new MessageQueueService(
            $view,
            $logger,
            $repositoryService,
            [],
            $repositoryService,
            $repositoryService,
            $repositoryService,
            $repositoryService,
            $repositoryService
        );

        $this->assertInstanceOf(MessageQueueService::class, $messageQueueService);
    }
}
