<?php

namespace Test\Unit\EmailSender\MessageLog\Application\Controller;

use EmailSender\EmailLog\Application\Controller\EmailLogController;
use PHPUnit\Framework\TestCase;
use Interop\Container\ContainerInterface;

/**
 * Class EmailLogControllerTest
 *
 * @package Test\Unit\EmailSender\MessageLog
 */
class EmailLogControllerTest extends TestCase
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

        $emailLogController = new EmailLogController($container);

        $this->assertInstanceOf(EmailLogController::class, $emailLogController);
    }
}
