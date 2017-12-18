<?php

namespace Test\Unit\EmailSender\Core\Catalog;

use EmailSender\Core\Catalog\RecipientsPropertyNameList;
use PHPUnit\Framework\TestCase;

/**
 * Class RecipientsPropertyNameListTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class RecipientsPropertyNameListTest extends TestCase
{
    /**
     * Test List elements.
     */
    public function testListElements()
    {
        $this->assertEquals('to',  RecipientsPropertyNameList::TO);
        $this->assertEquals('cc',  RecipientsPropertyNameList::CC);
        $this->assertEquals('bcc', RecipientsPropertyNameList::BCC);
    }
}
