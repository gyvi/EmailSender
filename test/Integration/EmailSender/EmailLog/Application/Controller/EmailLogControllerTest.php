<?php

namespace Test\Integration\EmailSender\EmailLog\Application\Controller;

use EmailSender\Core\Services\ServiceList;
use EmailSender\EmailLog\Application\Controller\EmailLogController;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailLogControllerTest
 *
 * @package Test\Integration\EmailSender\EmailLog
 */
class EmailLogControllerTest extends TestCase
{
    /**
     * Test list method.
     */
    public function testList()
    {
        $emailLogRepositoryReader = (new Mockery($this))->getRepositoryMock(true, null, []);
        $emailLogReaderService    = (new Mockery($this))->getRepositoryServiceMock($emailLogRepositoryReader);
        $container                = (new Mockery($this))->getContainerMock();

        $container->expects($this->any())
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        [ServiceList::VIEW,             (new Mockery($this))->getViewServiceMock()],
                        [ServiceList::LOGGER,           (new Mockery($this))->getLoggerMock()],
                        [ServiceList::EMAIL_LOG_READER, $emailLogReaderService],
                        [ServiceList::EMAIL_LOG_WRITER, (new Mockery($this))->getRepositoryServiceMock()],
                    ]
                )
            );

        $emailLogController = new EmailLogController($container);

        $get = [];

        $request = (new Mockery($this))->getServerRequestMock();
        $request->expects($this->exactly(2))
            ->method('getQueryParams')
            ->willReturn($get);

        $response = new Response();

        /** @var \Slim\Http\Response $result */
        $result = $emailLogController->list($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    /**
     * Test list method with invalid request.
     */
    public function testListWithInvalidRequest()
    {
        $logger = (new Mockery($this))->getLoggerMock();

        $logger->expects($this->once())
            ->method('warning')
            ->willReturn(null);

        $container = (new Mockery($this))->getContainerMock();

        $container->expects($this->any())
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        [ServiceList::VIEW,             (new Mockery($this))->getViewServiceMock()],
                        [ServiceList::LOGGER,           $logger],
                        [ServiceList::EMAIL_LOG_READER, (new Mockery($this))->getRepositoryServiceMock()],
                        [ServiceList::EMAIL_LOG_WRITER, (new Mockery($this))->getRepositoryServiceMock()],
                    ]
                )
            );

        $emailLogController = new EmailLogController($container);

        $get = [
            'invalidRequestProperty' => 'invalidRequestProperty'
        ];

        $request = (new Mockery($this))->getServerRequestMock();
        $request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn($get);

        $response = new Response();

        /** @var \Slim\Http\Response $result */
        $result = $emailLogController->list($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(400, $result->getStatusCode());
    }

    /**
     * Test lister method.
     */
    public function testLister()
    {
        $expected    = new Response();
        $view        = (new Mockery($this))->getViewMock();
        $viewService = (new Mockery($this))->getViewServiceMock($view);

        $view->expects($this->once())
            ->method('render')
            ->willReturn($expected);

        $container = (new Mockery($this))->getContainerMock();

        $container->expects($this->any())
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        [ServiceList::VIEW,             $viewService],
                        [ServiceList::LOGGER,           (new Mockery($this))->getLoggerMock()],
                        [ServiceList::EMAIL_LOG_READER, (new Mockery($this))->getRepositoryServiceMock()],
                        [ServiceList::EMAIL_LOG_WRITER, (new Mockery($this))->getRepositoryServiceMock()],
                    ]
                )
            );

        $emailLogController = new EmailLogController($container);

        $request  = (new Mockery($this))->getServerRequestMock();
        $response = new Response();

        /** @var \Slim\Http\Response $result */
        $result = $emailLogController->lister($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }
}
