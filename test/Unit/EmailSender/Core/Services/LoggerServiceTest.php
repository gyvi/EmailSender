<?php

namespace Test\Unit\EmailSender\Core\Services;

use EmailSender\Core\Services\LoggerService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerServiceTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class LoggerServiceTest extends TestCase
{
    /**
     * Test getService method.
     */
    public function testGetService()
    {
        $logger = (new LoggerService())->getService()();

        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
}
