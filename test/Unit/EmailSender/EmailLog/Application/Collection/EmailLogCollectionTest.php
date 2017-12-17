<?php

namespace Test\Unit\EmailSender\EmailLog\Application\Collection;

use EmailSender\EmailLog\Application\Collection\EmailLogCollection;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;
use ArrayIterator;

/**
 * Class EmailLogCollectionTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class EmailLogCollectionTest extends TestCase
{
    /**
     * Test getType method.
     */
    public function testGetType()
    {
        $emailLogCollection = new EmailLogCollection();

        $this->assertEquals(EmailLog::class, $emailLogCollection->getType());
    }

    /**
     * Test add with valid value.
     */
    public function testAddWithValidValue()
    {
        $emailLog           = (new Mockery($this))->getEmailLogMock();
        $emailLogCollection = new EmailLogCollection();

        $emailLogCollection->add($emailLog);
        $emailLogCollection->add($emailLog);

        $this->assertEquals([$emailLog, $emailLog], $emailLogCollection->toArray());
    }

    /**
     * @param $invalidType
     *
     * @dataProvider providerForTestAddWithInvalidValues
     *
     * @expectedException \InvalidArgumentException
     */
    public function testAddWithInvalidValues($invalidType)
    {
        $emailLogCollection = new EmailLogCollection();

        $emailLogCollection->add($invalidType);
    }

    /**
     * Test count and isEmpty methods.
     */
    public function testCountAndIsEmpty()
    {
        $emailLog           = (new Mockery($this))->getEmailLogMock();
        $emailLogCollection = new EmailLogCollection();

        $this->assertEquals(0, $emailLogCollection->count());
        $this->assertTrue($emailLogCollection->isEmpty());

        $emailLogCollection->add($emailLog);
        $emailLogCollection->add($emailLog);

        $this->assertEquals(2, $emailLogCollection->count());
        $this->assertFalse($emailLogCollection->isEmpty());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $emailLog           = (new Mockery($this))->getEmailLogMock();
        $emailLogCollection = new EmailLogCollection();

        $emailLogCollection->add($emailLog);
        $emailLogCollection->add($emailLog);

        $this->assertEquals([$emailLog, $emailLog], $emailLogCollection->jsonSerialize());
    }

    /**
     * Test getIterator method.
     */
    public function testGetIterator()
    {
        $emailLog           = (new Mockery($this))->getEmailLogMock();
        $emailLogCollection = new EmailLogCollection();

        $emailLogCollection->add($emailLog);
        $emailLogCollection->add($emailLog);

        $this->assertEquals(
            new ArrayIterator([$emailLog, $emailLog]),
            $emailLogCollection->getIterator()
        );
    }

    /**
     * Data provider for testAddWithInvalidValues.
     *
     * @return array
     */
    public function providerForTestAddWithInvalidValues(): array
    {
        return [
            [
                null,
            ],
            [
                new \stdClass(),
            ],
            [
                'string',
            ],
            [
                true,
            ],
            [
                1,
            ],
            [
                1.1,
            ],
        ];
    }
}
