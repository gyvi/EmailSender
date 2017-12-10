<?php

namespace Test\Unit\EmailSender\Email\Application\Route;

use EmailSender\Email\Application\Controller\EmailController;
use EmailSender\Email\Application\Route\Route;
use PHPUnit\Framework\TestCase;
use Slim\App;

/**
 * Class RouteTest
 *
 * @package Test\Unit\EmailSender\Email
 */
class RouteTest extends TestCase
{
    /**
     * Test init method.
     */
    public function testInit()
    {
        /** @var \Slim\App|\PHPUnit_Framework_MockObject_MockObject $application */
        $application = $this->getMockBuilder(App::class)
            ->disableOriginalConstructor()
            ->getMock();

        $application->expects($this->once())
            ->method('post')
            ->with(
                '/api/v1/emails',
                EmailController::class . ':add'
            )
            ->willReturn(null);

        $route = new Route($application);

        $route->init();
    }
}
