<?php

namespace Test\Helper\EmailSender\Mockery;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * Trait PsrMessageMock
 *
 * @package Test\Helper
 */
trait PsrMessageMock
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Psr\Http\Message\ServerRequestInterface
     */
    public function getServerRequestMock()
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \Psr\Http\Message\ServerRequestInterface|\PHPUnit_Framework_MockObject_MockObject $serverRequest */
        $serverRequest = $testCase->getMockBuilder(ServerRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $serverRequest;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Slim\Http\Response
     */
    public function getResponseMock()
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \Slim\Http\Response|\PHPUnit_Framework_MockObject_MockObject $response */
        $response = $testCase->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $response;
    }
}
