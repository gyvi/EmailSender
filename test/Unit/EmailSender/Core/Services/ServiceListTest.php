<?php

namespace Test\Unit\EmailSender\Core\Services;

use EmailSender\Core\Services\ServiceList;
use PHPUnit\Framework\TestCase;

/**
 * Class ServiceListTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class ServiceListTest extends TestCase
{
    public function testListElements()
    {
        $this->assertEquals('logger',              ServiceList::LOGGER);
        $this->assertEquals('errorHandler',        ServiceList::ERROR_HANDLER);
        $this->assertEquals('phpErrorHandler',     ServiceList::PHP_ERROR_HANDLER);
        $this->assertEquals('composedEmailReader', ServiceList::COMPOSED_EMAIL_READER);
        $this->assertEquals('composedEmailWriter', ServiceList::COMPOSED_EMAIL_WRITER);
        $this->assertEquals('emailLogReader',      ServiceList::EMAIL_LOG_READER);
        $this->assertEquals('emailLogWriter',      ServiceList::EMAIL_LOG_WRITER);
        $this->assertEquals('queue',               ServiceList::QUEUE);
        $this->assertEquals('smtp',                ServiceList::SMTP);
        $this->assertEquals('view',                ServiceList::VIEW);
    }
}
