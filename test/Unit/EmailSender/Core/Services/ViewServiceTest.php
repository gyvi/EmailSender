<?php

namespace Test\Unit\EmailSender\Core\Services;

use EmailSender\Core\Services\ViewService;
use PHPUnit\Framework\TestCase;
use Slim\Http\Uri;
use Slim\Interfaces\RouterInterface;
use Slim\Views\Twig;
use Test\Helper\EmailSender\Mockery;

/**
 * Class ViewServiceTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class ViewServiceTest extends TestCase
{
    /**
     * Test getService method.
     */
    public function testGetService()
    {
        $viewService = (new ViewService())->getService();
        $container   = (new Mockery($this))->getContainerMock();

        $uri = $this->getMockBuilder(Uri::class)
            ->disableOriginalConstructor()
            ->getMock();

        $uri->expects($this->once())
            ->method('getBasePath')
            ->willReturn('/');

        $request = (new Mockery($this))->getServerRequestMock();

        $request->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $router = $this->getMockBuilder(RouterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $container->expects($this->any())
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        ['request', $request],
                        ['router',  $router],
                    ]
                )
            );

        $view = $viewService($container)();

        $this->assertInstanceOf(Twig::class, $view);
    }
}
