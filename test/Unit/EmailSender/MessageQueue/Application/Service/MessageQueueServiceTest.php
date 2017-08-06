<?php

namespace Test\Unit\EmailSender\MessageQueue\Application\Service;

use EmailSender\MessageQueue\Application\Service\MessageQueueService;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
use PHPUnit\Framework\TestCase;
use Closure;

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

        /** @var EmailComposerInterface|\PHPUnit_Framework_MockObject_MockObject $emailComposer */
        $emailComposer = $this->getMockBuilder(EmailComposerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $messageQueueService = new MessageQueueService(
            $emailComposer,
            $repositoryService,
            $repositoryService,
            $repositoryService,
            $repositoryService
        );

        $this->assertInstanceOf(MessageQueueService::class, $messageQueueService);
    }
}
