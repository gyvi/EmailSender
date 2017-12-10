<?php

namespace Test\Unit\EmailSender\ComposedEmail\Infrastructure\Service;

use EmailSender\ComposedEmail\Infrastructure\Service\SMTPException;
use PHPUnit\Framework\TestCase;

/**
 * Class SMTPExceptionTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class SMTPExceptionTest extends TestCase
{
    /**
     * Test exception throw.
     *
     * @expectedException \EmailSender\ComposedEmail\Infrastructure\Service\SMTPException
     */
    public function testThrow()
    {
        throw new SMTPException('any message');
    }
}
