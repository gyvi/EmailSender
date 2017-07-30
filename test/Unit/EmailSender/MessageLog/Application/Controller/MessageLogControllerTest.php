<?php

namespace Test\Unit\EmailSender\MessageLog\Application\Controller;

use EmailSender\MessageLog\Application\Controller\MessageLogController;
use PHPUnit\Framework\TestCase;
use Interop\Container\ContainerInterface;

/**
 * Class MessageLogControllerTest
 *
 * @package Test\Unit\EmailSender\MessageLog
 */
class MessageLogControllerTest extends TestCase
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

        $messageQueueController = new MessageLogController($container);

        $this->assertInstanceOf(MessageLogController::class, $messageQueueController);
    }
}
