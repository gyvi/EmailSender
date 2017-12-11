<?php

namespace Test\Unit\EmailSender\EmailLog\Infrastructure\Persistence;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\EmailLog\Infrastructure\Persistence\EmailLogRepositoryWriter;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailLogRepositoryWriterTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class EmailLogRepositoryWriterTest extends TestCase
{
    /**
     * Test add method with valid values.
     */
    public function testAddWithValidValues()
    {
        $emailAddress      = [
            EmailAddressPropertyNameList::ADDRESS => 'emailAddress',
            EmailAddressPropertyNameList::NAME    => null,
        ];

        $emailLogId        = 1;
        $expected          = new UnsignedInteger($emailLogId);
        $repository        = (new Mockery($this))->getRepositoryMock(true, null, null, $emailLogId);
        $repositoryService = (new Mockery($this))->getRepositoryServiceMock($repository);

        $emailLog = (new Mockery($this))->getEmailLogMock(
            null,
            1,
            $emailAddress,
            [$emailAddress],
            'subject',
            null,
            null,
            null,
            1
        );

        $emailLogRepositoryWriter = new EmailLogRepositoryWriter($repositoryService);

        $this->assertEquals($expected, $emailLogRepositoryWriter->add($emailLog));
    }

    /**
     * Test add method with exception.
     *
     * @expectedException \PDOException
     * @expectedExceptionMessage Unable to write to the database.
     */
    public function testAddWithException()
    {
        $emailAddress      = [
            EmailAddressPropertyNameList::ADDRESS => 'emailAddress',
            EmailAddressPropertyNameList::NAME    => null,
        ];

        $repository        = (new Mockery($this))->getRepositoryMock(false);
        $repositoryService = (new Mockery($this))->getRepositoryServiceMock($repository);

        $emailLog = (new Mockery($this))->getEmailLogMock(
            null,
            1,
            $emailAddress,
            [$emailAddress],
            'subject',
            null,
            null,
            null,
            1
        );

        $emailLogRepositoryWriter = new EmailLogRepositoryWriter($repositoryService);

        $emailLogRepositoryWriter->add($emailLog);
    }

    /**
     * Test setStatus method with valid values.
     *
     * @param int    $emailStatus
     * @param string $errorMessageString
     *
     * @dataProvider providerForTestSetStatusWithValidValues
     */
    public function testSetStatusWithValidValues(int $emailStatus, string $errorMessageString)
    {
        $repository        = (new Mockery($this))->getRepositoryMock(true);
        $repositoryService = (new Mockery($this))->getRepositoryServiceMock($repository);
        $emailLogId        = (new Mockery($this))->getUnSignedIntegerMock(1);
        $emailLogId->expects($this->once())
            ->method('getValue')
            ->willReturn(1);

        $errorMessage = (new Mockery($this))->getStringLiteralMock($errorMessageString);

        if ($emailStatus === EmailStatusList::STATUS_ERROR) {
            $emailLogId->expects($this->once())
                ->method('getValue')
                ->willReturn($errorMessageString);
        }

        $emailLogRepositoryWriter = new EmailLogRepositoryWriter($repositoryService);

        $emailLogRepositoryWriter->setStatus(
            $emailLogId,
            (new Mockery($this))->getEmailStatusMock($emailStatus),
            $errorMessage
        );
    }

    /**
     * Test setStatus method with exception.
     *
     * @expectedException \PDOException
     * @expectedExceptionMessage Unable to write to the database.
     */
    public function testSetStatusWithException()
    {
        $repository        = (new Mockery($this))->getRepositoryMock(false);
        $repositoryService = (new Mockery($this))->getRepositoryServiceMock($repository);
        $emailLogId        = (new Mockery($this))->getUnSignedIntegerMock(1);
        $errorMessage      = (new Mockery($this))->getStringLiteralMock('errorMessage');
        $emailStatus       = (new Mockery($this))->getEmailStatusMock(EmailStatusList::STATUS_QUEUED);

        $emailLogRepositoryWriter = new EmailLogRepositoryWriter($repositoryService);

        $emailLogRepositoryWriter->setStatus($emailLogId, $emailStatus, $errorMessage);
    }

    /**
     * Data porovider for testSetStatusWithValidValues method.
     *
     * @return array
     */
    public function providerForTestSetStatusWithValidValues(): array
    {
        return [
            [
                EmailStatusList::STATUS_QUEUED,
                'error',
            ],
            [
                EmailStatusList::STATUS_SENT,
                'error',
            ],
            [
                EmailStatusList::STATUS_ERROR,
                'error',
            ],
        ];
    }

}
