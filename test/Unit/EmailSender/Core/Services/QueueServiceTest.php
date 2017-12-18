<?php

namespace Test\Unit\EmailSender\Core\Services;

use EmailSender\Core\Services\QueueService;
use EmailSender\Core\Services\ServiceList;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class QueueServiceTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class QueueServiceTest extends TestCase
{
    /**
     * Test getService method.
     *
     * @expectedException ErrorException
     * @expectedExceptionMessage stream_socket_client(): unable to connect to tcp://localhost:65535 (Connection refused)
     */
    public function testGetService()
    {
        $queueService = (new QueueService())->getService();

        $container = (new Mockery($this))->getContainerMock();

        $container->expects($this->once())
            ->method('get')
            ->willReturn(
                [
                    ServiceList::QUEUE => [
                        'host'     => 'localhost',
                        'port'     => '65535',
                        'username' => 'user',
                        'password' => 'pass',
                    ]
                ]
            );

        $queueService($container)();
    }
}
