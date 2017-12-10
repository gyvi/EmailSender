<?php

namespace Test\Unit\EmailSender\ComposedEmail\Domain\Service;

use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryWriterInterface;
use EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory;
use EmailSender\ComposedEmail\Domain\Service\AddComposedEmailService;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class AddComposedEmailServiceTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class AddComposedEmailServiceTest extends TestCase
{
    /**
     * Test add method.
     */
    public function testAdd()
    {
        $composedEmailId = (new Mockery($this))->getUnSignedIntegerMock(1);
        $email           = (new Mockery($this))->getEmailMock();
        $composedEmail   = (new Mockery($this))->getComposedEmailMock();

        $composedEmail->expects($this->once())
            ->method('setComposedEmailId')
            ->willReturn(null);

        /** @var \EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory|\PHPUnit_Framework_MockObject_MockObject $composedEmailFactory */
        $composedEmailFactory = $this->getMockBuilder(ComposedEmailFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $composedEmailFactory->expects($this->once())
            ->method('create')
            ->willReturn($composedEmail);

        /** @var \EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryWriterInterface|\PHPUnit_Framework_MockObject_MockObject $repositoryWriter */
        $repositoryWriter = $this->getMockBuilder(ComposedEmailRepositoryWriterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repositoryWriter->expects($this->once())
            ->method('add')
            ->willReturn($composedEmailId);

        $addComposedEmailService = new AddComposedEmailService($repositoryWriter, $composedEmailFactory);

        $this->assertEquals($composedEmail, $addComposedEmailService->add($email));
    }
}
