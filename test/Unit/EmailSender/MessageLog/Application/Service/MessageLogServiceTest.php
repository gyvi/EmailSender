<?php

namespace Test\Unit\EmailSender\MessageLog\Application\Service;

use EmailSender\MessageLog\Application\Service\MessageLogService;
use PHPUnit\Framework\TestCase;
use Closure;
use Psr\Log\LoggerInterface;

/**
 * Class MessageLogServiceTest
 *
 * @package Test\Unit\EmailSender\MessageLog
 */
class MessageLogServiceTest extends TestCase
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

        $messageLogService = new MessageLogService(
            $logger,
            $repositoryService,
            $repositoryService
        );

        $this->assertInstanceOf(MessageLogService::class, $messageLogService);
    }
}
