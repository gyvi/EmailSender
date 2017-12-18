<?php

namespace Test\Unit\EmailSender\Core\ValueObject;

use EmailSender\Core\ValueObject\Subject;
use PHPUnit\Framework\TestCase;

/**
 * Class SubjectTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class SubjectTest extends TestCase
{
    /**
     * Test __construct with valid values.
     */
    public function testConstructWithValidValues()
    {
        $this->assertInstanceOf(Subject::class, new Subject(''));
        $this->assertInstanceOf(Subject::class, new Subject('Something'));
        $this->assertInstanceOf(Subject::class, new Subject(1));
        $this->assertInstanceOf(Subject::class, new Subject(1.1));
        $this->assertInstanceOf(Subject::class, new Subject(5000));
        $this->assertInstanceOf(Subject::class, new Subject(-5000));
    }

    /**
     * Test __construct with invalid values.
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage Invalid Subject.
     */
    public function testConstructWithInvalidValues()
    {
        new Subject(str_pad('', Subject::MAX_LENGTH + 1, ' '));
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
        new Subject($value);
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
