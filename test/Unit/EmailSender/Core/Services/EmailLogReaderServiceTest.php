<?php

namespace Test\Unit\EmailSender\Core\Services;

use EmailSender\Core\Services\EmailLogReaderService;
use EmailSender\Core\Services\ServiceList;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailLogReaderServiceTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class EmailLogReaderServiceTest extends TestCase
{
    /**
     * Test getService method.
     *
     * @expectedException \PDOException
     * @expectedExceptionMessage SQLSTATE[HY000] [2002] Connection refused
     */
    public function testGetService()
    {
        $emailLogReaderService = (new EmailLogReaderService())->getService();

        $container = (new Mockery($this))->getContainerMock();

        $container->expects($this->once())
            ->method('get')
            ->willReturn(
                [
                    ServiceList::EMAIL_LOG_READER => [
                        'dsn'      => 'mysql:host=localhost:65535;dbname=emailSender;charset=UTF8',
                        'username' => 'user',
                        'password' => 'pass',
                        'options'  => [],
                    ]
                ]
            );

        $emailLogReaderService($container)();
    }
}
