<?php

namespace Test\Unit\EmailSender\EmailLog\Application\Exception;

use EmailSender\EmailLog\Application\Exception\EmailLogException;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailLogExceptionTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class EmailLogExceptionTest extends TestCase
{
    /**
     * Test exception throw.
     *
     * @expectedException \EmailSender\EmailLog\Application\Exception\EmailLogException
     */
    public function testThrow()
    {
        throw new EmailLogException('error message');
    }
}
