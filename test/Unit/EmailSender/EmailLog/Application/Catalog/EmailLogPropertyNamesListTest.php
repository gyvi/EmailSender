<?php

namespace Test\Unit\EmailSender\EmailLog\Application\Catalog;

use EmailSender\EmailLog\Application\Catalog\EmailLogPropertyNamesList;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailLogPropertyNamesListTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class EmailLogPropertyNamesListTest extends TestCase
{
    /**
     * Test list elements.
     */
    public function testListElements()
    {
        $this->assertEquals('emailLogId',      EmailLogPropertyNamesList::EMAIL_LOG_ID);
        $this->assertEquals('composedEmailId', EmailLogPropertyNamesList::COMPOSED_EMAIL_ID);
        $this->assertEquals('from',            EmailLogPropertyNamesList::FROM);
        $this->assertEquals('recipients',      EmailLogPropertyNamesList::RECIPIENTS);
        $this->assertEquals('subject',         EmailLogPropertyNamesList::SUBJECT);
        $this->assertEquals('logged',          EmailLogPropertyNamesList::LOGGED);
        $this->assertEquals('queued',          EmailLogPropertyNamesList::QUEUED);
        $this->assertEquals('sent',            EmailLogPropertyNamesList::SENT);
        $this->assertEquals('delay',           EmailLogPropertyNamesList::DELAY);
        $this->assertEquals('status',          EmailLogPropertyNamesList::STATUS);
        $this->assertEquals('errorMessage',    EmailLogPropertyNamesList::ERROR_MESSAGE);
    }
}
