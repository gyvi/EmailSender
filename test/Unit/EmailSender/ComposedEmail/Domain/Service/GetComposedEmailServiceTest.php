<?php

namespace Test\Unit\EmailSender\ComposedEmail\Domain\Service;

use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface;
use EmailSender\ComposedEmail\Domain\Service\GetComposedEmailService;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class GetComposedEmailServiceTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class GetComposedEmailServiceTest extends TestCase
{
    /**
     * Test get method.
     */
    public function testGet()
    {
        $composedEmailId = (new Mockery($this))->getUnSignedIntegerMock(1);
        $composedEmail   = (new Mockery($this))->getComposedEmailMock();

        /** @var \EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface|\PHPUnit_Framework_MockObject_MockObject $repositoryReader */
        $repositoryReader = $this->getMockBuilder(ComposedEmailRepositoryReaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repositoryReader->expects($this->once())
            ->method('get')
            ->willReturn($composedEmail);

        $getComposerEmailService = new GetComposedEmailService($repositoryReader);

        $this->assertEquals($composedEmail, $getComposerEmailService->get($composedEmailId));
    }
}
