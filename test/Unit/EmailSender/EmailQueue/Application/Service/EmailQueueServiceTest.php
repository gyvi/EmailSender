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
        /** @var Closure $repositoryService */
        $repositoryService = function () {};

        /** @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var Closure $view */
        $view = function () {};

        $emailQueueService = new EmailQueueService(
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

        $this->assertInstanceOf(EmailQueueService::class, $emailQueueService);
    }
}
