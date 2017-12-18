<?php

namespace Test\Unit\EmailSender\Core\ValueObject;

use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\Core\ValueObject\EmailStatus;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailStatusTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class EmailStatusTest extends TestCase
{
    /**
     * Test __construct with valid values.
     */
    public function testConstructWithValidValues()
    {
        $this->assertInstanceOf(EmailStatus::class, new EmailStatus(EmailStatusList::ERROR));
        $this->assertInstanceOf(EmailStatus::class, new EmailStatus(EmailStatusList::LOGGED));
        $this->assertInstanceOf(EmailStatus::class, new EmailStatus(EmailStatusList::QUEUED));
        $this->assertInstanceOf(EmailStatus::class, new EmailStatus(EmailStatusList::SENT));
    }

    /**
     * Test __construct with invalid values.
     *
     * @param string $value
     *
     * @dataProvider providerForTestConstructWithInvalidValues
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage Invalid EmailStatus.
     */
    public function testConstructWithInvalidValues(string $value)
    {
        new EmailStatus($value);
    }

    /**
     * Test __construct with invalid values.
     *
     * @param mixed $value
     *
     * @dataProvider providerForTestConstructWithInvalidTypes
     *
     * @expectedException \TypeError
     */
    public function testConstructWithInvalidTypes($value)
    {
        new EmailStatus($value);
    }

    /**
     * Data provider for testConstructWithInvalidValues.
     *
     * @return array
     */
    public function providerForTestConstructWithInvalidValues(): array
    {
        return [
            [
                -5000,
            ],
            [
                50000,
            ],
            [
                '',
            ],
            [
                'something',
            ],
            [
                1.1
            ],
        ];
    }

    /**
     * Data provider for testConstructWithInvalidTypes.
     *
     * @return array
     */
    public function providerForTestConstructWithInvalidTypes(): array
    {
        return [
            [
                null,
            ],
            [
                new \stdClass(),
            ],
            [
                [],
            ],
        ];
    }
}
