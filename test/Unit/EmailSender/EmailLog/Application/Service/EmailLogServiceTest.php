<?php

namespace Test\Unit\EmailSender\EmailLog\Application\Service;

use EmailSender\EmailLog\Application\Service\EmailLogService;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

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
        $repositoryService = (new Mockery($this))->getRepositoryServiceMock();
        $logger            = (new Mockery($this))->getLoggerMock();
        $view              = (new Mockery($this))->getViewServiceMock();

        $emailLogService = new EmailLogService(
            $view,
            $logger,
            $repositoryService,
            $repositoryService
        );

        $this->assertInstanceOf(EmailLogService::class, $emailLogService);
    }
}
