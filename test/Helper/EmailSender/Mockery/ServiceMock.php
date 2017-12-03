<?php

namespace Test\Helper\EmailSender\Mockery;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Log\LoggerInterface;
use PHPUnit_Framework_MockObject_MockObject;
use Closure;
use PDO;
use Slim\Views\Twig;
use SMTP;

/**
 * Trait ServiceMock
 *
 * @package Test\Helper
 */
trait ServiceMock
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Psr\Log\LoggerInterface
     */
    public function getLoggerMock(): PHPUnit_Framework_MockObject_MockObject
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        return $testCase->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param null|\PHPUnit_Framework_MockObject_MockObject $repositoryMock
     *
     * @return \Closure
     */
    public function getRepositoryServiceMock(?PHPUnit_Framework_MockObject_MockObject $repositoryMock = null): Closure
    {
        /** @return \PHPUnit_Framework_MockObject_MockObject|\PDO */
        return function () use ($repositoryMock): ?PDO {
            return $repositoryMock;
        };
    }

    /**
     * @return \PDO|PHPUnit_Framework_MockObject_MockObject
     */
    public function getRepositoryMock(): PDO
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        return $testCase->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param null|\PHPUnit_Framework_MockObject_MockObject $queueConnectionMock
     *
     * @return \Closure
     */
    public function getQueueServiceMock(?PHPUnit_Framework_MockObject_MockObject $queueConnectionMock = null): Closure
    {
        /** @return \PHPUnit_Framework_MockObject_MockObject|\PhpAmqpLib\Connection\AMQPStreamConnection */
        return function () use ($queueConnectionMock): ?AMQPStreamConnection {
            return $queueConnectionMock;
        };
    }

    /**
     * @return \PhpAmqpLib\Connection\AMQPStreamConnection|PHPUnit_Framework_MockObject_MockObject
     */
    public function getQueueConnectionMock(): AMQPStreamConnection
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        return $testCase->getMockBuilder(AMQPStreamConnection::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param null|\PHPUnit_Framework_MockObject_MockObject $smtpMock
     *
     * @return \Closure
     */
    public function getSMTPServiceMock(?PHPUnit_Framework_MockObject_MockObject $smtpMock = null): Closure
    {
        /** @return \PHPUnit_Framework_MockObject_MockObject|\SMTP */
        return function () use ($smtpMock): ?SMTP {
            return $smtpMock;
        };
    }

    /**
     * @return \SMTP|PHPUnit_Framework_MockObject_MockObject
     */
    public function getSMTPMock(): SMTP
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        return $testCase->getMockBuilder(SMTP::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param null|\PHPUnit_Framework_MockObject_MockObject $viewMock
     *
     * @return \Closure
     */
    public function getViewServiceMock(?PHPUnit_Framework_MockObject_MockObject $viewMock = null): Closure
    {
        /** @return \PHPUnit_Framework_MockObject_MockObject|\Slim\Views\Twig */
        return function () use ($viewMock): ?Twig {
            return $viewMock;
        };
    }

    /**
     * @return \Slim\Views\Twig|PHPUnit_Framework_MockObject_MockObject
     */
    public function getViewMock(): Twig
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        return $testCase->getMockBuilder(Twig::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
