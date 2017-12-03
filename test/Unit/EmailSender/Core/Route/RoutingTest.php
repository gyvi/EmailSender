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
     * @return \EmailSender\Core\Route\RouteInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getRouteMock(): RouteInterface
    {
        return $this->getMockBuilder(RouteInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Test add method.
     */
    public function testAdd()
    {
        $route = $this->getRouteMock();

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
        $route = $this->getRouteMock();

        $route->expects($this->once())
            ->method('init');

        $routing = new Routing();
        $routing->add($route);
        $routing->init();
    }
}
