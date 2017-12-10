<?php

namespace Test\Unit\EmailSender\Email\Application\Catalog;

use EmailSender\Email\Application\Catalog\EmailPropertyNameList;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailPropertyNameListTest
 *
 * @package Test\Unit\EmailSender\Email
 */
class EmailPropertyNameListTest extends TestCase
{
    /**
     * Test EmailPropertyName List elements.
     */
    public function testListElements()
    {
        $this->assertEquals('from',    EmailPropertyNameList::FROM);
        $this->assertEquals('to',      EmailPropertyNameList::TO);
        $this->assertEquals('cc',      EmailPropertyNameList::CC);
        $this->assertEquals('bcc',     EmailPropertyNameList::BCC);
        $this->assertEquals('subject', EmailPropertyNameList::SUBJECT);
        $this->assertEquals('body',    EmailPropertyNameList::BODY);
        $this->assertEquals('replyTo', EmailPropertyNameList::REPLY_TO);
        $this->assertEquals('delay',   EmailPropertyNameList::DELAY);
    }
}
