<?php

namespace Test\Unit\EmailSender\EmailLog\Application\Service;

use EmailSender\EmailLog\Application\Service\EmailLogService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class EmailLogServiceTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class EmailLogServiceTest extends TestCase
{
    /**
     * Test __construct method.
     */
    public function testConstruct()
    {
        /** @var \Closure $repositoryService */
        $repositoryService = function () {};

        /** @var \Psr\Log\LoggerInterface|\PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \Closure $view */
        $view = function () {};

        $emailLogService = new EmailLogService(
            $view,
            $logger,
            $repositoryService,
            $repositoryService
        );

        $this->assertInstanceOf(EmailLogService::class, $emailLogService);
    }
}
