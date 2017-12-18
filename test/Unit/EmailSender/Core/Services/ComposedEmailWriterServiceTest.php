<?php

namespace Test\Unit\EmailSender\Core\Services;

use EmailSender\Core\Services\ComposedEmailWriterService;
use EmailSender\Core\Services\ServiceList;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class ComposedEmailWriterServiceTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class ComposedEmailWriterServiceTest extends TestCase
{
    /**
     * Test getService method.
     *
     * @expectedException \PDOException
     * @expectedExceptionMessage SQLSTATE[HY000] [2002] Connection refused
     */
    public function testGetService()
    {
        $composedEmailWriterService = (new ComposedEmailWriterService())->getService();

        $container = (new Mockery($this))->getContainerMock();

        $container->expects($this->once())
            ->method('get')
            ->willReturn(
                [
                    ServiceList::COMPOSED_EMAIL_WRITER => [
                        'dsn'      => 'mysql:host=localhost:65535;dbname=emailSender;charset=UTF8',
                        'username' => 'user',
                        'password' => 'pass',
                        'options'  => [],
                    ]
                ]
            );

        $composedEmailWriterService($container)();
    }
}
