<?php

namespace Test\Unit\EmailSender\EmailLog\Domain\Service;

use EmailSender\EmailLog\Application\Collection\EmailLogCollection;
use EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface;
use EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest;
use EmailSender\EmailLog\Domain\Factory\ListEmailLogRequestFactory;
use EmailSender\EmailLog\Domain\Service\ListEmailLogService;
use PHPUnit\Framework\TestCase;

/**
 * Class ListEmailLogServiceTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class ListEmailLogServiceTest extends TestCase
{
    /**
     * Test list method.
     */
    public function testList()
    {
        /** @var \EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest|\PHPUnit_Framework_MockObject_MockObject $listEmailLooRequest */
        $listEmailLooRequest = $this->getMockBuilder(ListEmailLogRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \EmailSender\EmailLog\Application\Collection\EmailLogCollection|\PHPUnit_Framework_MockObject_MockObject $emailLogCollection */
        $emailLogCollection = $this->getMockBuilder(EmailLogCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface|\PHPUnit_Framework_MockObject_MockObject $repositoryReader */
        $repositoryReader = $this->getMockBuilder(EmailLogRepositoryReaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repositoryReader->expects($this->once())
            ->method('list')
            ->willReturn($emailLogCollection);

        /** @var \EmailSender\EmailLog\Domain\Factory\ListEmailLogRequestFactory|\PHPUnit_Framework_MockObject_MockObject $listEmailLogRequestFactory */
        $listEmailLogRequestFactory = $this->getMockBuilder(ListEmailLogRequestFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $listEmailLogRequestFactory->expects($this->once())
            ->method('create')
            ->willReturn($listEmailLooRequest);

        $listEmailLogService = new ListEmailLogService($repositoryReader, $listEmailLogRequestFactory);

        $this->assertEquals($emailLogCollection, $listEmailLogService->list([]));
    }
}
