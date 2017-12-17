<?php

namespace Test\Integration\EmailSender\Email\Application\Service;

use EmailSender\Email\Application\Catalog\EmailPropertyNameList;
use EmailSender\Email\Application\Service\EmailService;
use PhpAmqpLib\Channel\AMQPChannel;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailServiceTest
 *
 * @package Test\Integration\EmailSender\Email
 */
class EmailServiceTest extends TestCase
{
    /**
     * Test add with delay = 0.
     */
    public function testAddWithSent()
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

        $post = [
            EmailPropertyNameList::FROM    => 'test@test.com',
            EmailPropertyNameList::TO      => 'test@test.com',
            EmailPropertyNameList::SUBJECT => 'subject',
            EmailPropertyNameList::BODY    => 'body',
            EmailPropertyNameList::DELAY   => '0',
        ];

        $request = (new Mockery($this))->getServerRequestMock();
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($post);

        $response = new Response();

        $emailService = new EmailService(
            (new Mockery($this))->getViewServiceMock(),
            (new Mockery($this))->getLoggerMock(),
            (new Mockery($this))->getQueueServiceMock(),
            [],
            (new Mockery($this))->getRepositoryServiceMock(),
            $composedEmailWriterService,
            (new Mockery($this))->getRepositoryServiceMock(),
            $emailLogWriterService,
            $smtpService
        );

        /** @var \Slim\Http\Response $result */
        $result = $emailService->add($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(204, $result->getStatusCode());
    }

    /**
     * Test add with delay > 0.
     */
    public function testAddWithQueued()
    {
        $queueServiceSettings = [
            'queue'    => 'emailSendQueue',
            'exchange' => 'emailSender',
        ];

        $composedEmailRepositoryWriter = (new Mockery($this))->getRepositoryMock(true, null, null, 1);
        $composedEmailWriterService    = (new Mockery($this))->getRepositoryServiceMock($composedEmailRepositoryWriter);
        $emailLogRepositoryWriter      = (new Mockery($this))->getRepositoryMock(true, null, null, 1);
        $emailLogWriterService         = (new Mockery($this))->getRepositoryServiceMock($emailLogRepositoryWriter);

        /** @var \PhpAmqpLib\Channel\AMQPChannel|\PHPUnit_Framework_MockObject_MockObject $channel */
        $channel = $this->getMockBuilder(AMQPChannel::class)
            ->disableOriginalConstructor()
            ->setMethods([
                    'channel',
                    'exchange_declare',
                    'queue_declare',
                    'queue_bind',
                    'basic_publish',
                    'close']
            )->getMock();

        $queue = (new Mockery($this))->getQueueConnectionMock();

        $queue->expects($this->once())
            ->method('channel')
            ->willReturn($channel);

        $queue->expects($this->once())
            ->method('close')
            ->willReturn(null);

        $queueService = (new Mockery($this))->getQueueServiceMock($queue);

        $post = [
            EmailPropertyNameList::FROM    => 'test@test.com',
            EmailPropertyNameList::TO      => 'test@test.com',
            EmailPropertyNameList::SUBJECT => 'subject',
            EmailPropertyNameList::BODY    => 'body',
            EmailPropertyNameList::DELAY   => '1',
        ];

        $request = (new Mockery($this))->getServerRequestMock();
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($post);

        $response = new Response();

        $emailService = new EmailService(
            (new Mockery($this))->getViewServiceMock(),
            (new Mockery($this))->getLoggerMock(),
            $queueService,
            $queueServiceSettings,
            (new Mockery($this))->getRepositoryServiceMock(),
            $composedEmailWriterService,
            (new Mockery($this))->getRepositoryServiceMock(),
            $emailLogWriterService,
            (new Mockery($this))->getSMTPServiceMock()
        );

        /** @var \Slim\Http\Response $result */
        $result = $emailService->add($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(201, $result->getStatusCode());
    }

    /**
     * Test add with an invalid request property.
     */
    public function testAddWithInvalidRequestProperty()
    {
        $logger = (new Mockery($this))->getLoggerMock();

        $logger->expects($this->once())
            ->method('warning')
            ->willReturn(null);

        $post = [
            EmailPropertyNameList::FROM    => 'wrongEmailAddress',
            EmailPropertyNameList::TO      => 'test@test.com',
            EmailPropertyNameList::SUBJECT => 'subject',
            EmailPropertyNameList::BODY    => 'body',
            EmailPropertyNameList::DELAY   => '1',
        ];

        $request = (new Mockery($this))->getServerRequestMock();
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($post);

        $response = new Response();

        $emailService = new EmailService(
            (new Mockery($this))->getViewServiceMock(),
            $logger,
            (new Mockery($this))->getQueueServiceMock(),
            [],
            (new Mockery($this))->getRepositoryServiceMock(),
            (new Mockery($this))->getRepositoryServiceMock(),
            (new Mockery($this))->getRepositoryServiceMock(),
            (new Mockery($this))->getRepositoryServiceMock(),
            (new Mockery($this))->getSMTPServiceMock()
        );

        /** @var \Slim\Http\Response $result */
        $result = $emailService->add($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(400, $result->getStatusCode());
    }

    /**
     * Test add with an service error.
     */
    public function testAddWithServiceError()
    {
        $composedEmailRepositoryWriter = (new Mockery($this))->getRepositoryMock(false, null, null, 1);
        $composedEmailWriterService    = (new Mockery($this))->getRepositoryServiceMock($composedEmailRepositoryWriter);

        $post = [
            EmailPropertyNameList::FROM    => 'test@test.com',
            EmailPropertyNameList::TO      => 'test@test.com',
            EmailPropertyNameList::SUBJECT => 'subject',
            EmailPropertyNameList::BODY    => 'body',
            EmailPropertyNameList::DELAY   => '1',
        ];

        $request = (new Mockery($this))->getServerRequestMock();
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($post);

        $response = new Response();

        $emailService = new EmailService(
            (new Mockery($this))->getViewServiceMock(),
            (new Mockery($this))->getLoggerMock(),
            (new Mockery($this))->getQueueServiceMock(),
            [],
            (new Mockery($this))->getRepositoryServiceMock(),
            $composedEmailWriterService,
            (new Mockery($this))->getRepositoryServiceMock(),
            (new Mockery($this))->getRepositoryServiceMock(),
            (new Mockery($this))->getSMTPServiceMock()
        );

        /** @var \Slim\Http\Response $result */
        $result = $emailService->add($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(500, $result->getStatusCode());
    }
}
