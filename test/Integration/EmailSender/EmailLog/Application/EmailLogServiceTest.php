<?php

namespace Test\Integration\EmailSender\EmailLog\Application;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\Core\Catalog\RecipientsPropertyNameList;
use EmailSender\EmailLog\Application\Catalog\ListEmailLogRequestPropertyNameList;
use EmailSender\EmailLog\Application\Service\EmailLogService;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailLogServiceTest
 *
 * @package Test\Integration\EmailSender
 */
class EmailLogServiceTest extends TestCase
{
    /**
     * Test add method with valid values.
     */
    public function testAdd()
    {
        $emailLogId               = 1;
        $viewService              = (new Mockery($this))->getViewServiceMock();
        $logger                   = (new Mockery($this))->getLoggerMock();
        $emailLogReaderService    = (new Mockery($this))->getRepositoryServiceMock();
        $emailLogRepositoryWriter = (new Mockery($this))->getRepositoryMock(true, null, null, $emailLogId);
        $emailLogWriterService    = (new Mockery($this))->getRepositoryServiceMock($emailLogRepositoryWriter);

        $email = (new Mockery($this))->getEmailMock(
            [
                EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                EmailAddressPropertyNameList::NAME    => null,
            ],
            null,
            null,
            null,
            'subject',
            null,
            null,
            1
        );

        $composedEmail = (new Mockery($this))->getComposedEmailMock(
            1,
            null,
            [
                RecipientsPropertyNameList::TO  => [],
                RecipientsPropertyNameList::CC  => [],
                RecipientsPropertyNameList::BCC => [],
            ]
        );

        $emailLogService = new EmailLogService($viewService, $logger, $emailLogReaderService, $emailLogWriterService);

        $this->assertInstanceOf(EmailLog::class, $emailLogService->add($email, $composedEmail));
    }

    /**
     * Test add method with repository exception.
     *
     * @expectedException \EmailSender\EmailLog\Application\Exception\EmailLogException
     * @expectedExceptionMessage Something went wrong when adding email log.
     */
    public function testAddWithRepositoryException()
    {
        $viewService              = (new Mockery($this))->getViewServiceMock();
        $logger                   = (new Mockery($this))->getLoggerMock();
        $emailLogReaderService    = (new Mockery($this))->getRepositoryServiceMock();
        $emailLogRepositoryWriter = (new Mockery($this))->getRepositoryMock(false);
        $emailLogWriterService    = (new Mockery($this))->getRepositoryServiceMock($emailLogRepositoryWriter);

        $email = (new Mockery($this))->getEmailMock(
            [
                EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                EmailAddressPropertyNameList::NAME    => null,
            ],
            null,
            null,
            null,
            'subject',
            null,
            null,
            1
        );

        $composedEmail = (new Mockery($this))->getComposedEmailMock(
            1,
            null,
            [
                RecipientsPropertyNameList::TO  => [],
                RecipientsPropertyNameList::CC  => [],
                RecipientsPropertyNameList::BCC => [],
            ]
        );

        $emailLogService = new EmailLogService($viewService, $logger, $emailLogReaderService, $emailLogWriterService);

        $emailLogService->add($email, $composedEmail);
    }

    /**
     * Test setStatus method with valid values.
     */
    public function testSetStatus()
    {
        $viewService              = (new Mockery($this))->getViewServiceMock();
        $logger                   = (new Mockery($this))->getLoggerMock();
        $emailLogReaderService    = (new Mockery($this))->getRepositoryServiceMock();
        $emailLogRepositoryWriter = (new Mockery($this))->getRepositoryMock(true);
        $emailLogWriterService    = (new Mockery($this))->getRepositoryServiceMock($emailLogRepositoryWriter);

        $emailLogId     = (new Mockery($this))->getUnSignedIntegerMock(1);
        $emailLogStatus = (new Mockery($this))->getEmailStatusMock(EmailStatusList::STATUS_SENT);
        $errorMessage   = '';

        $emailLogService = new EmailLogService($viewService, $logger, $emailLogReaderService, $emailLogWriterService);

        $emailLogService->setStatus($emailLogId, $emailLogStatus, $errorMessage);

        $this->assertTrue(true); // Test void return.
    }

    /**
     * Test setStatus method with with repository exception.
     *
     * @expectedException \EmailSender\EmailLog\Application\Exception\EmailLogException
     * @expectedExceptionMessage Something went wrong when setting email log status.
     */
    public function testSetStatusWithRepositoryException()
    {
        $viewService              = (new Mockery($this))->getViewServiceMock();
        $logger                   = (new Mockery($this))->getLoggerMock();
        $emailLogReaderService    = (new Mockery($this))->getRepositoryServiceMock();
        $emailLogRepositoryWriter = (new Mockery($this))->getRepositoryMock(false);
        $emailLogWriterService    = (new Mockery($this))->getRepositoryServiceMock($emailLogRepositoryWriter);

        $emailLogId     = (new Mockery($this))->getUnSignedIntegerMock(1);
        $emailLogStatus = (new Mockery($this))->getEmailStatusMock(EmailStatusList::STATUS_SENT);
        $errorMessage   = '';

        $emailLogService = new EmailLogService($viewService, $logger, $emailLogReaderService, $emailLogWriterService);

        $emailLogService->setStatus($emailLogId, $emailLogStatus, $errorMessage);
    }

    /**
     * Test list method.
     */
    public function testList()
    {
        $viewService              = (new Mockery($this))->getViewServiceMock();
        $logger                   = (new Mockery($this))->getLoggerMock();
        $emailLogRepositoryReader = (new Mockery($this))->getRepositoryMock(true, null, []);
        $emailLogReaderService    = (new Mockery($this))->getRepositoryServiceMock($emailLogRepositoryReader);
        $emailLogWriterService    = (new Mockery($this))->getRepositoryServiceMock();

        $emailLogService = new EmailLogService($viewService, $logger, $emailLogReaderService, $emailLogWriterService);

        $get = [];

        $request = (new Mockery($this))->getServerRequestMock();
        $request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn($get);

        $response = new Response();

        /** @var \Slim\Http\Response $result */
        $result = $emailLogService->list($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    /**
     * Test list method.
     */
    public function testListWithInvalidRequest()
    {
        $viewService           = (new Mockery($this))->getViewServiceMock();
        $logger                = (new Mockery($this))->getLoggerMock();
        $emailLogReaderService = (new Mockery($this))->getRepositoryServiceMock();
        $emailLogWriterService = (new Mockery($this))->getRepositoryServiceMock();

        $emailLogService = new EmailLogService($viewService, $logger, $emailLogReaderService, $emailLogWriterService);

        $get = [
            ListEmailLogRequestPropertyNameList::LAST_COMPOSED_EMAIL_ID => 'something'
        ];

        $request = (new Mockery($this))->getServerRequestMock();
        $request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn($get);

        $response = new Response();

        /** @var \Slim\Http\Response $result */
        $result = $emailLogService->list($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(400, $result->getStatusCode());
    }

    /**
     * Test list method.
     */
    public function testListWithRepositoryException()
    {
        $viewService              = (new Mockery($this))->getViewServiceMock();
        $logger                   = (new Mockery($this))->getLoggerMock();
        $emailLogRepositoryReader = (new Mockery($this))->getRepositoryMock(false);
        $emailLogReaderService    = (new Mockery($this))->getRepositoryServiceMock($emailLogRepositoryReader);
        $emailLogWriterService    = (new Mockery($this))->getRepositoryServiceMock();

        $emailLogService = new EmailLogService($viewService, $logger, $emailLogReaderService, $emailLogWriterService);

        $get = [];

        $request = (new Mockery($this))->getServerRequestMock();
        $request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn($get);

        $response = new Response();

        /** @var \Slim\Http\Response $result */
        $result = $emailLogService->list($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(500, $result->getStatusCode());
    }

    /**
     * Test lister method.
     */
    public function testLister()
    {
        $expected = new Response();

        $view = (new Mockery($this))->getViewMock();

        $view->expects($this->once())
            ->method('render')
            ->willReturn($expected);

        $viewService           = (new Mockery($this))->getViewServiceMock($view);
        $logger                = (new Mockery($this))->getLoggerMock();
        $emailLogReaderService = (new Mockery($this))->getRepositoryServiceMock();
        $emailLogWriterService = (new Mockery($this))->getRepositoryServiceMock();

        $emailLogService = new EmailLogService($viewService, $logger, $emailLogReaderService, $emailLogWriterService);

        $request  = (new Mockery($this))->getServerRequestMock();
        $response = new Response();

        /** @var \Slim\Http\Response $result */
        $result = $emailLogService->lister($request, $response, []);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }
}
