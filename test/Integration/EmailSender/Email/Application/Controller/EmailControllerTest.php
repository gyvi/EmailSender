<?php

namespace Test\Integration\EmailSender\Email\Application\Controller;

use EmailSender\Core\Services\ServiceList;
use EmailSender\Email\Application\Catalog\EmailPropertyNameList;
use EmailSender\Email\Application\Controller\EmailController;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailControllerTest
 *
 * @package Test\Integration\EmailSender\Email
 */
class EmailControllerTest extends TestCase
{
    /**
     * Test add method.
     */
    public function testAdd()
    {
        $composedEmailRepositoryWriter = (new Mockery($this))->getRepositoryMock(true, null, null, 1);
        $composedEmailWriterService    = (new Mockery($this))->getRepositoryServiceMock($composedEmailRepositoryWriter);
        $emailLogRepositoryWriter      = (new Mockery($this))->getRepositoryMock(true, null, null, 1);
        $emailLogWriterService         = (new Mockery($this))->getRepositoryServiceMock($emailLogRepositoryWriter);

        $smtp = (new Mockery($this))->getSMTPMock();
        $smtp->expects($this->once())
            ->method('mail')
            ->willReturn(true);

        $smtp->expects($this->any())
            ->method('recipient')
            ->willReturn(true);

        $smtp->expects($this->once())
            ->method('data')
            ->willReturn(true);

        $smtpService = (new Mockery($this))->getSMTPServiceMock($smtp);

        /** @var \Interop\Container\ContainerInterface|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $container->expects($this->any())
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        [ServiceList::VIEW,                  (new Mockery($this))->getViewServiceMock()],
                        [ServiceList::LOGGER,                (new Mockery($this))->getLoggerMock()],
                        [ServiceList::COMPOSED_EMAIL_READER, (new Mockery($this))->getRepositoryServiceMock()],
                        [ServiceList::COMPOSED_EMAIL_WRITER, $composedEmailWriterService],
                        [ServiceList::EMAIL_LOG_READER,      (new Mockery($this))->getRepositoryServiceMock()],
                        [ServiceList::EMAIL_LOG_WRITER,      $emailLogWriterService],
                        [ServiceList::QUEUE,                 (new Mockery($this))->getQueueServiceMock()],
                        [ServiceList::SMTP,                  $smtpService],
                        ['settings',                         [ServiceList::QUEUE => []]],
                    ]
                )
            );

        $emailController = new EmailController($container);

        $post = [
            EmailPropertyNameList::FROM    => 'test@test.com',
            EmailPropertyNameList::TO      => 'test@test.com',
            EmailPropertyNameList::SUBJECT => 'subject',
            EmailPropertyNameList::BODY    => 'body',
            EmailPropertyNameList::DELAY   => '0',
        ];

        $request = (new Mockery($this))->getServerRequestMock();
        $request->expects($this->exactly(2))
            ->method('getParsedBody')
            ->willReturn($post);

        $response = new Response();

        /** @var \Slim\Http\Response $result */
        $result = $emailController->add($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(204, $result->getStatusCode());
    }

    /**
     * Test add with invalid request.
     */
    public function testAddWithInvalidRequest()
    {
        $logger = (new Mockery($this))->getLoggerMock();
        $logger->expects($this->once())
            ->method('warning')
            ->willReturn(null);

        /** @var \Interop\Container\ContainerInterface|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $container->expects($this->any())
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        [ServiceList::VIEW,                  (new Mockery($this))->getViewServiceMock()],
                        [ServiceList::LOGGER,                $logger],
                        [ServiceList::COMPOSED_EMAIL_READER, (new Mockery($this))->getRepositoryServiceMock()],
                        [ServiceList::COMPOSED_EMAIL_WRITER, (new Mockery($this))->getRepositoryServiceMock()],
                        [ServiceList::EMAIL_LOG_READER,      (new Mockery($this))->getRepositoryServiceMock()],
                        [ServiceList::EMAIL_LOG_WRITER,      (new Mockery($this))->getRepositoryServiceMock()],
                        [ServiceList::QUEUE,                 (new Mockery($this))->getQueueServiceMock()],
                        [ServiceList::SMTP,                  (new Mockery($this))->getSMTPServiceMock()],
                        ['settings',                         [ServiceList::QUEUE => []]],
                    ]
                )
            );

        $emailController = new EmailController($container);

        $post = [
            'invalidRequestProperty' => 'invalidRequestValue',
        ];

        $request = (new Mockery($this))->getServerRequestMock();
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($post);

        $response = new Response();

        /** @var \Slim\Http\Response $result */
        $result = $emailController->add($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(400, $result->getStatusCode());
    }
}
