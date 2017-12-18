<?php

namespace Test\Unit\EmailSender\Core\Services;

use EmailSender\Core\Services\ErrorHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Test\Helper\EmailSender\Mockery;
use Closure;
use Exception;
use InvalidArgumentException;

/**
 * Class ErrorHandlerTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class ErrorHandlerTest extends TestCase
{
    /**
     * @return \Closure
     */
    public function getErrorHandler(): Closure
    {
        $logger = (new Mockery($this))->getLoggerMock();

        $logger->expects($this->once())
            ->method('alert')
            ->willReturn(null);

        $container = (new Mockery($this))->getContainerMock();
        $container->expects($this->once())
            ->method('get')
            ->willReturn($logger);

        $errorHandler = (new ErrorHandler())->getService();

        return $errorHandler($container);
    }

    /**
     * Test getService method.
     */
    public function testGetService()
    {
        $serviceRequest = (new Mockery($this))->getServerRequestMock();
        $response       = new Response();
        $throwable      = new Exception();

        $errorHandler = $this->getErrorHandler();

        /** @var \Slim\Http\Response $result */
        $result = $errorHandler($serviceRequest, $response, $throwable);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(500, $result->getStatusCode());
    }

    /**
     * Test getService method.
     */
    public function testGetServiceWithInvalidArgumentException()
    {
        $serviceRequest = (new Mockery($this))->getServerRequestMock();
        $response       = new Response();
        $throwable      = new InvalidArgumentException('', 0, new Exception());

        $errorHandler = $this->getErrorHandler();

        /** @var \Slim\Http\Response $result */
        $result = $errorHandler($serviceRequest, $response, $throwable);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(400, $result->getStatusCode());
    }
}
