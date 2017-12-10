<?php

namespace Test\Unit\EmailSender\EmailQueue\Application\Exception;

use EmailSender\EmailQueue\Application\Exception\EmailQueueException;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailQueueExceptionTest
 *
 * @package Test\Unit\EmailSender\EmailQueue
 */
class EmailQueueExceptionTest extends TestCase
{
    /**
     * Test exception throw.
     *
     * @expectedException \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     */
    public function testThrow()
    {
        throw new EmailQueueException('any message');
    }
}
