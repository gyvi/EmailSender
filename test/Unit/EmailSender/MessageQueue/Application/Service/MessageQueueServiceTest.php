<?php

namespace Test\Unit\EmailSender\MessageQueue\Application\Service;

use EmailSender\MessageQueue\Application\Service\MessageQueueService;
use PHPUnit\Framework\TestCase;

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
        $messageQueueService = new MessageQueueService();

        $this->assertInstanceOf(MessageQueueService::class, $messageQueueService);
    }
}
