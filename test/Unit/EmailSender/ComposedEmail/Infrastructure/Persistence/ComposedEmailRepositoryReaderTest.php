<?php

namespace Test\Unit\EmailSender\ComposedEmail\Infrastructure\Persistence;

use EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory;
use EmailSender\ComposedEmail\Infrastructure\Persistence\ComposedEmailRepositoryReader;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class ComposedEmailRepositoryReaderTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class ComposedEmailRepositoryReaderTest extends TestCase
{
    /**
     * Test get method with valid values.
     */
    public function testGet()
    {
        $composedEmailIdValue       = 1;
        $composedEmailId            = (new Mockery($this))->getUnSignedIntegerMock($composedEmailIdValue);
        $expected                   = (new Mockery($this))->getComposedEmailMock($composedEmailIdValue);
        $repository                 = (new Mockery($this))->getRepositoryMock(true, []);
        $composedEmailReaderService = (new Mockery($this))->getRepositoryServiceMock($repository);

        /** @var \EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory|\PHPUnit_Framework_MockObject_MockObject $composedEmailFactory */
        $composedEmailFactory = $this->getMockBuilder(ComposedEmailFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $composedEmailFactory->expects($this->once())
            ->method('createFromArray')
            ->willReturn($expected);

        $composedEmailRepositoryReader = new ComposedEmailRepositoryReader(
            $composedEmailReaderService,
            $composedEmailFactory
        );

        $this->assertEquals($expected, $composedEmailRepositoryReader->get($composedEmailId));
    }

    /**
     * Test get method with database error.
     *
     * @expectedException \PDOException
     * @expectedExceptionMessage Unable read from the database.
     */
    public function testGetWithDatabaseError()
    {
        $composedEmailIdValue       = 1;
        $composedEmailId            = (new Mockery($this))->getUnSignedIntegerMock($composedEmailIdValue);
        $repository                 = (new Mockery($this))->getRepositoryMock(false);
        $composedEmailReaderService = (new Mockery($this))->getRepositoryServiceMock($repository);

        /** @var \EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory|\PHPUnit_Framework_MockObject_MockObject $composedEmailFactory */
        $composedEmailFactory = $this->getMockBuilder(ComposedEmailFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $composedEmailRepositoryReader = new ComposedEmailRepositoryReader(
            $composedEmailReaderService,
            $composedEmailFactory
        );

        $composedEmailRepositoryReader->get($composedEmailId);
    }
}
