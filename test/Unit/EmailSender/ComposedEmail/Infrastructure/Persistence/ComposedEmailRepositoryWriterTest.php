<?php

namespace Test\Unit\EmailSender\ComposedEmail\Infrastructure\Persistence;

use EmailSender\ComposedEmail\Infrastructure\Persistence\ComposedEmailRepositoryWriter;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class ComposedEmailRepositoryWriterTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class ComposedEmailRepositoryWriterTest extends TestCase
{
    /**
     * Test add method with successful attempt.
     */
    public function testAdd()
    {
        $expectedValue              = 1;
        $expected                   = new UnsignedInteger($expectedValue);
        $repository                 = (new Mockery($this))->getRepositoryMock(true, null, null, $expectedValue);
        $composedEmailWriterService = (new Mockery($this))->getRepositoryServiceMock($repository);
        $composedEmail              = (new Mockery($this))->getComposedEmailMock();

        $composedEmailRepositoryWriter = new ComposedEmailRepositoryWriter($composedEmailWriterService);

        $this->assertEquals($expected, $composedEmailRepositoryWriter->add($composedEmail));
    }

    /**
     * Test add method with database error.
     *
     * @expectedException \PDOException
     * @expectedExceptionMessage Unable write to the database.
     */
    public function testAddWithDatabaseError()
    {
        $repository                 = (new Mockery($this))->getRepositoryMock(false);
        $composedEmailWriterService = (new Mockery($this))->getRepositoryServiceMock($repository);
        $composedEmail              = (new Mockery($this))->getComposedEmailMock();

        $composedEmailRepositoryWriter = new ComposedEmailRepositoryWriter($composedEmailWriterService);

        $composedEmailRepositoryWriter->add($composedEmail);
    }
}
