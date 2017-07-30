<?php

namespace Test\Unit\EmailSender\MessageLog\Application\Service;

use EmailSender\MessageLog\Application\Service\MessageLogService;
use PHPUnit\Framework\TestCase;

/**
 * Class MessageLogServiceTest
 *
 * @package Test\Unit\EmailSender\MessageLog
 */
class MessageLogServiceTest extends TestCase
{
    /**
     * Test __construct method.
     */
    public function testConstruct()
    {
        $messageLogService = new MessageLogService();

        $this->assertInstanceOf(MessageLogService::class, $messageLogService);
    }
}
