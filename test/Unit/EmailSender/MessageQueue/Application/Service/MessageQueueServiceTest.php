<?php

namespace Test\Unit\EmailSender\MessageQueue\Application\Service;

use EmailSender\MessageQueue\Application\Service\MessageQueueService;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
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
        $emailBuilder = $this->getMockBuilder(EmailComposerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $messageQueueService = new MessageQueueService($emailBuilder);

        $this->assertInstanceOf(MessageQueueService::class, $messageQueueService);
    }
}
