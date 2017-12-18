<?php

namespace Test\Unit\EmailSender\Core\Framework;

use EmailSender\Core\Framework\Bootstrap;
use EmailSender\Core\Services\ServiceList;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Container;
use Slim\Interfaces\RouterInterface;
use Test\Helper\EmailSender\Mockery;

/**
 * Class BootstrapTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class BootstrapTest extends TestCase
{
    /**
     * Test init method.
     */
    public function testInit()
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

        $router = $this->getMockBuilder(RouterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $addedServices = [
            ServiceList::LOGGER,
            ServiceList::ERROR_HANDLER,
            ServiceList::PHP_ERROR_HANDLER,
            ServiceList::COMPOSED_EMAIL_READER,
            ServiceList::COMPOSED_EMAIL_WRITER,
            ServiceList::EMAIL_LOG_READER,
            ServiceList::EMAIL_LOG_WRITER,
            ServiceList::QUEUE,
            ServiceList::SMTP,
            ServiceList::VIEW,
        ];

        foreach ($addedServices as $index => $service) {
            $container->expects($this->at($index))
                ->method('offsetSet')
                ->with($service, $closure)
                ->willReturn(null);
        }

        $container->expects($this->any())
            ->method('get')
            ->with('router')
            ->willReturn($router);

        $bootstrap = new Bootstrap($container);

        $app = $bootstrap->init();

        $this->assertInstanceOf(App::class, $app);
    }
}
