<?php

namespace Test\Unit\EmailSender\EmailQueue\Application\Service;

use EmailSender\EmailQueue\Application\Service\EmailQueueService;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

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
        $queueService = (new Mockery($this))->getQueueServiceMock();
        $logger       = (new Mockery($this))->getLoggerMock();

        $emailQueueService = new EmailQueueService(
            $logger,
            $queueService,
            []
        );

        $this->assertInstanceOf(EmailQueueService::class, $emailQueueService);
    }
}
