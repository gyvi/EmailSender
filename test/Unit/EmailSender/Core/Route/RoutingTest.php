<?php

namespace Test\Unit\EmailSender\Core\Route;

use EmailSender\Core\Route\RouteInterface;
use EmailSender\Core\Route\Routing;
use PHPUnit\Framework\TestCase;

/**
 * Class RoutingTest
 *
 * @package Test\Unit\EmailSender
 */
class RoutingTest extends TestCase
{
    /**
     * Test add method.
     */
    public function testAdd()
    {
        /** @var RouteInterface|\PHPUnit_Framework_MockObject_MockObject $route */
        $route = $this->getMockBuilder(RouteInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $route->expects($this->never())
            ->method('init');

        $routing = new Routing();
        $routing->add($route);
    }

    /**
     * Test init method.
     */
    public function testInit()
    {
        /** @var RouteInterface|\PHPUnit_Framework_MockObject_MockObject $route */
        $route = $this->getMockBuilder(RouteInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $route->expects($this->once())
            ->method('init');

        $routing = new Routing();
        $routing->add($route);
        $routing->init();
    }
}
