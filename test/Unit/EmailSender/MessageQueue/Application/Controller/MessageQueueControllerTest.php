<?php

namespace Test\Unit\EmailSender\MessageQueue\Application\Controller;

use EmailSender\MessageQueue\Application\Controller\MessageQueueController;
use PHPUnit\Framework\TestCase;
use Interop\Container\ContainerInterface;

/**
 * Class MessageQueueControllerTest
 *
 * @package Test\Unit\EmailSender\MessageQueue
 */
class MessageQueueControllerTest extends TestCase
{
    /**
     * Test __construct method.
     */
    public function testConstruct()
    {
        /** @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $messageQueueController = new MessageQueueController($container);

        $this->assertInstanceOf(MessageQueueController::class, $messageQueueController);
    }
}
