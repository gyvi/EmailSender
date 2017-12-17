<?php

namespace Test\Unit\EmailSender\EmailLog\Domain\Factory;

use EmailSender\EmailLog\Application\Collection\EmailLogCollection;
use EmailSender\EmailLog\Domain\Factory\EmailLogCollectionFactory;
use EmailSender\EmailLog\Domain\Factory\EmailLogFactory;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailLogCollectionFactoryTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class EmailLogCollectionFactoryTest extends TestCase
{
    /**
     * Test create method.
     *
     * @param array $emailLogCollectionArray
     *
     * @dataProvider providerForTestCreate
     */
    public function testCreate(array $emailLogCollectionArray)
    {
        $count = count($emailLogCollectionArray);

        /** @var \EmailSender\EmailLog\Domain\Factory\EmailLogFactory|\PHPUnit_Framework_MockObject_MockObject $emailLogFactory */
        $emailLogFactory = $this->getMockBuilder(EmailLogFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $emailLog = (new Mockery($this))->getEmailLogMock();

        $emailLogFactory->expects($this->exactly($count))
            ->method('createFromArray')
            ->willReturn($emailLog);

        $emailLogCollectionFactory = new EmailLogCollectionFactory($emailLogFactory);

        $emailLogCollection = $emailLogCollectionFactory->create($emailLogCollectionArray);

        $this->assertInstanceOf(EmailLogCollection::class, $emailLogCollection);

        $this->assertEquals($count, $emailLogCollection->count());
    }

    /**
     * Data provider for testCreate method.
     *
     * @return array
     */
    public function providerForTestCreate(): array
    {
        return [
            [
                [],
            ],
            [
                [
                    ['EmailLog1']
                ],
            ],
            [
                [
                    ['EmailLog1']
                ],
                [
                    ['EmailLog2']
                ],
                [
                    ['EmailLog3']
                ],
            ],
        ];
    }
}
