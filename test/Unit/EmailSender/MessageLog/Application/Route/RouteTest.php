<?php

namespace Test\Unit\EmailSender\MessageLog\Application\Route;

use EmailSender\MessageLog\Application\Route\Route;
use PHPUnit\Framework\TestCase;
use Slim\App;

/**
 * Class RouteTest
 *
 * @package Test\Unit\EmailSender
 */
class RouteTest extends TestCase
{
    /**
     * Test init method.
     */
    public function testInit()
    {
        /** @var App|\PHPUnit_Framework_MockObject_MockObject $application */
        $application = $this->getMockBuilder(App::class)
            ->disableOriginalConstructor()
            ->getMock();

        $application->expects($this->exactly(2))
            ->method('get');

        $application->expects($this->never())
            ->method('post');

        $route = new Route($application);
        $route->init();
    }
}
