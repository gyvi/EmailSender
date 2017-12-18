<?php

namespace Test\Unit\EmailSender\Core\Services;

use EmailSender\Core\Services\ServiceList;
use EmailSender\Core\Services\SMTPService;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;
use SMTP;

/**
 * Class SMTPServiceTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class SMTPServiceTest extends TestCase
{
    /**
     * Test getService method.
     */
    public function testGetService()
    {
        $smtpService = (new SMTPService())->getService();
        $container   = (new Mockery($this))->getContainerMock();

        $container->expects($this->once())
            ->method('get')
            ->willReturn(
                [
                    ServiceList::SMTP => [
                        'host'     => 'localhost',
                        'port'     => '25',
                        'username' => 'user',
                        'password' => 'pass',
                    ]
                ]
            );

        $smtp = $smtpService($container)();

        $this->assertInstanceOf(SMTP::class, $smtp);
    }

    /**
     * Test getService method with exception.
     *
     * @expectedException \EmailSender\ComposedEmail\Infrastructure\Service\SMTPException
     * @expectedExceptionMessage Unable to connect to the SMTP server: localhost:65535
     */
    public function testGetServiceWithException()
    {
        $smtpService = (new SMTPService())->getService();
        $container   = (new Mockery($this))->getContainerMock();

        $container->expects($this->once())
            ->method('get')
            ->willReturn(
                [
                    ServiceList::SMTP => [
                        'host'     => 'localhost',
                        'port'     => '65535',
                        'username' => 'user',
                        'password' => 'pass',
                    ]
                ]
            );

        $smtpService($container)();
    }
}
