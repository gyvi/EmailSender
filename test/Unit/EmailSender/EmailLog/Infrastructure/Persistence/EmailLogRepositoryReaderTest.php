<?php

namespace Test\Unit\EmailSender\EmailLog\Infrastructure\Persistence;

use EmailSender\EmailLog\Application\Collection\EmailLogCollection;
use EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest;
use EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory;
use EmailSender\EmailLog\Infrastructure\Persistence\EmailLogRepositoryReader;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailLogRepositoryReaderTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class EmailLogRepositoryReaderTest extends TestCase
{
    /**
     * Test list method with valid values.
     *
     * @param int $page
     *
     * @dataProvider providerForTestListWithGoodValues
     */
    public function testListWithGoodValues($page)
    {
        $repository        = (new Mockery($this))->getRepositoryMock(true, null, []);
        $repositoryService = (new Mockery($this))->getRepositoryServiceMock($repository);

        /** @var \EmailSender\EmailLog\Application\Collection\EmailLogCollection|\PHPUnit_Framework_MockObject_MockObject $emailLogCollection */
        $emailLogCollection = $this->getMockBuilder(EmailLogCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory|\PHPUnit_Framework_MockObject_MockObject $emailLogCollectionFactory */
        $emailLogCollectionFactory = $this->getMockBuilder(EmailLogCollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $emailLogCollectionFactory->expects($this->once())
            ->method('create')
            ->willReturn($emailLogCollection);

        /** @var \EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest|\PHPUnit_Framework_MockObject_MockObject $listEmailLogRequest */
        $listEmailLogRequest = $this->getMockBuilder(ListEmailLogRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $listEmailLogRequest->expects($this->any())
            ->method('getFrom')
            ->willReturn((new Mockery($this))->getEmailAddressMock('emailAddress'));

        $listEmailLogRequest->expects($this->any())
            ->method('getPerPage')
            ->willReturn((new Mockery($this))->getUnSignedIntegerMock(5));

        $listEmailLogRequest->expects($this->any())
            ->method('getLastComposedEmailId')
            ->willReturn((new Mockery($this))->getUnSignedIntegerMock(2));

        $listEmailLogRequest->expects($this->any())
            ->method('getPage')
            ->willReturn((new Mockery($this))->getUnSignedIntegerMock($page));

        $emailLogRepositoryReader = new EmailLogRepositoryReader($repositoryService, $emailLogCollectionFactory);

        $this->assertEquals($emailLogCollection, $emailLogRepositoryReader->list($listEmailLogRequest));
    }

    /**
     * Test list method with exception.
     *
     * @expectedException \PDOException
     * @expectedExceptionMessage Unable read from the database.
     */
    public function testListWithException()
    {
        $repository        = (new Mockery($this))->getRepositoryMock(false);
        $repositoryService = (new Mockery($this))->getRepositoryServiceMock($repository);

        /** @var \EmailSender\EmailLog\Application\Collection\EmailLogCollection|\PHPUnit_Framework_MockObject_MockObject $emailLogCollection */
        $emailLogCollection = $this->getMockBuilder(EmailLogCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory|\PHPUnit_Framework_MockObject_MockObject $emailLogCollectionFactory */
        $emailLogCollectionFactory = $this->getMockBuilder(EmailLogCollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest|\PHPUnit_Framework_MockObject_MockObject $listEmailLogRequest */
        $listEmailLogRequest = $this->getMockBuilder(ListEmailLogRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $emailLogRepositoryReader = new EmailLogRepositoryReader($repositoryService, $emailLogCollectionFactory);

        $this->assertEquals($emailLogCollection, $emailLogRepositoryReader->list($listEmailLogRequest));
    }

    /**
     * Data provider for testListWithGoodValues method.
     *
     * @return array
     */
    public function providerForTestListWithGoodValues(): array
    {
        return [
            [
                1
            ],
            [
                0
            ],
        ];
    }
}
