<?php

namespace Test\Unit\EmailSender\Core\Services;

use EmailSender\Core\Services\EmailLogWriterService;
use EmailSender\Core\Services\ServiceList;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailLogWriterServiceTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class EmailLogWriterServiceTest extends TestCase
{
    /**
     * Test getService method.
     *
     * @expectedException \PDOException
     * @expectedExceptionMessage SQLSTATE[HY000] [2002] Connection refused
     */
    public function testGetService()
    {
        $emailLogWriterService = (new EmailLogWriterService())->getService();

        $container = (new Mockery($this))->getContainerMock();

        $container->expects($this->once())
            ->method('get')
            ->willReturn(
                [
                    ServiceList::EMAIL_LOG_WRITER => [
                        'dsn'      => 'mysql:host=localhost:65535;dbname=emailSender;charset=UTF8',
                        'username' => 'user',
                        'password' => 'pass',
                        'options'  => [],
                    ]
                ]
            );

        $emailLogWriterService($container)();
    }
}
