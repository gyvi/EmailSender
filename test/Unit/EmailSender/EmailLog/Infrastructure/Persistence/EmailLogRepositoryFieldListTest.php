<?php

namespace Test\Unit\EmailSender\EmailLog\Infrastructure\Persistence;

use EmailSender\EmailLog\Infrastructure\Persistence\EmailLogRepositoryFieldList;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailLogRepositoryFieldListTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class EmailLogRepositoryFieldListTest extends TestCase
{
    /**
     * Test EmailLog repository field list elements.
     */
    public function testListElements()
    {
        $this->assertEquals('emailLogId',      EmailLogRepositoryFieldList::EMAIL_LOG_ID);
        $this->assertEquals('composedEmailId', EmailLogRepositoryFieldList::COMPOSED_EMAIL_ID);
        $this->assertEquals('from',            EmailLogRepositoryFieldList::FROM);
        $this->assertEquals('recipients',      EmailLogRepositoryFieldList::RECIPIENTS);
        $this->assertEquals('subject',         EmailLogRepositoryFieldList::SUBJECT);
        $this->assertEquals('logged',          EmailLogRepositoryFieldList::LOGGED);
        $this->assertEquals('queued',          EmailLogRepositoryFieldList::QUEUED);
        $this->assertEquals('sent',            EmailLogRepositoryFieldList::SENT);
        $this->assertEquals('delay',           EmailLogRepositoryFieldList::DELAY);
        $this->assertEquals('status',          EmailLogRepositoryFieldList::STATUS);
        $this->assertEquals('errorMessage',    EmailLogRepositoryFieldList::ERROR_MESSAGE);
    }
}
