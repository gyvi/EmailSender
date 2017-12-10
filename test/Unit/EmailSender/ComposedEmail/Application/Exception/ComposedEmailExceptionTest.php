<?php

namespace Test\Unit\EmailSender\ComposedEmail\Application\Exception;

use EmailSender\ComposedEmail\Application\Exception\ComposedEmailException;
use PHPUnit\Framework\TestCase;

/**
 * Class ComposedEmailExceptionTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class ComposedEmailExceptionTest extends TestCase
{
    /**
     * Test exception throw.
     *
     * @expectedException \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     */
    public function testThrow()
    {
        throw new ComposedEmailException('error message');
    }
}
