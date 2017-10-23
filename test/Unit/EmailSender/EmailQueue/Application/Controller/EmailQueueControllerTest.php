<?php

namespace Test\Unit\EmailSender\EmailQueue\Application\Controller;

use EmailSender\EmailQueue\Application\Controller\EmailQueueController;
use PHPUnit\Framework\TestCase;
use Interop\Container\ContainerInterface;

/**
 * Class EmailQueueControllerTest
 *
 * @package Test\Unit\EmailSender\MessageQueue
 */
class EmailQueueControllerTest extends TestCase
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

        $emailQueueController = new EmailQueueController($container);

        $this->assertInstanceOf(EmailQueueController::class, $emailQueueController);
    }
}
