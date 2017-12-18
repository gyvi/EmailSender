<?php

namespace Test\Unit\EmailSender\Core\Services;

use EmailSender\Core\Services\PhpErrorHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Test\Helper\EmailSender\Mockery;
use Exception;

/**
 * Class PhpErrorHandlerTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class PhpErrorHandlerTest extends TestCase
{
    /**
     * Test getService method.
     */
    public function testGetService()
    {
        $logger = (new Mockery($this))->getLoggerMock();

        $logger->expects($this->once())
            ->method('alert')
            ->willReturn(null);

        $container = (new Mockery($this))->getContainerMock();
        $container->expects($this->once())
            ->method('get')
            ->willReturn($logger);

        $phpErrorHandler = (new PhpErrorHandler())->getService()($container);

        $serviceRequest = (new Mockery($this))->getServerRequestMock();
        $response       = new Response();
        $throwable      = new Exception();

        /** @var \Slim\Http\Response $result */
        $result = $phpErrorHandler($serviceRequest, $response, $throwable);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(500, $result->getStatusCode());
    }
}
