<?php

namespace Test\Unit\EmailSender\Core\Scalar\Application\Exception;

use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use PHPUnit\Framework\TestCase;

/**
 * Class ValueObjectExceptionTest
 *
 * @package Test\Unit\EmailSender\Core\Scalar
 */
class ValueObjectExceptionTest extends TestCase
{
    /**
     * Test exception throw.
     *
     * @expectedException \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @expectedExceptionMessage error message
     */
    public function testThrow()
    {
        throw new ValueObjectException('error message');
    }
}
