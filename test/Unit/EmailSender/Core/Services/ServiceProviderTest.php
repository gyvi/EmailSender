<?php

namespace Test\Unit\EmailSender\Core\Services;

use EmailSender\Core\Services\ServiceList;
use EmailSender\Core\Services\ServiceProvider;
use PHPUnit\Framework\TestCase;
use Slim\Container;
use Test\Helper\EmailSender\Mockery;

/**
 * Class ServiceProviderTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class ServiceProviderTest extends TestCase
{
    /**
     * Test addService and init methods.
     */
    public function testAddServiceAndInit()
    {
        $closure          = function(){};
        $serviceInterface = (new Mockery($this))->getServiceInterfaceMock();

        $serviceInterface->expects($this->any())
            ->method('getService')
            ->willReturn($closure);

        /** @var \Slim\Container|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $container->expects($this->at(0))
            ->method('offsetSet')
            ->with(ServiceList::LOGGER, $closure)
            ->willReturn(null);

        $container->expects($this->at(1))
            ->method('offsetSet')
            ->with(ServiceList::QUEUE, $closure)
            ->willReturn(null);

        $serviceProvider = new ServiceProvider($container);

        $serviceProvider->addService(ServiceList::LOGGER, $serviceInterface);
        $serviceProvider->addService(ServiceList::QUEUE, $serviceInterface);

        $serviceProvider->init();
    }
}
