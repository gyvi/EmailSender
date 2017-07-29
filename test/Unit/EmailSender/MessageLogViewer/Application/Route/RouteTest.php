<?php

namespace Test\Unit\EmailSender\MessageLogViewer\Application\Route;

use EmailSender\MessageLogViewer\Application\Route\Route;
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
     * Test __construct method.
     */
    public function testConstruct()
    {
        /** @var App|\PHPUnit_Framework_MockObject_MockObject $application */
        $application = $this->getMockBuilder(App::class)
            ->disableOriginalConstructor()
            ->getMock();

       $route = new Route($application);

       $this->assertInstanceOf(Route::class, $route);
    }

    /**
     * Test init method.
     */
    public function testInit()
    {
        /** @var App|\PHPUnit_Framework_MockObject_MockObject $application */
        $application = $this->getMockBuilder(App::class)
            ->disableOriginalConstructor()
            ->getMock();

        $application->expects($this->exactly(1))
            ->method('get');

        $route = new Route($application);
        $route->init();
    }
}
