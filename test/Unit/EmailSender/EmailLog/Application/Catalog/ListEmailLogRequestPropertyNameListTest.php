<?php

namespace Test\Unit\EmailSender\EmailLog\Application\Catalog;

use EmailSender\EmailLog\Application\Catalog\ListEmailLogRequestPropertyNameList;
use PHPUnit\Framework\TestCase;

/**
 * Class ListEmailLogRequestPropertyNameListTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class ListEmailLogRequestPropertyNameListTest extends TestCase
{
    /**
     * Test list elements.
     */
    public function testListElements()
    {
        $this->assertEquals('from',                ListEmailLogRequestPropertyNameList::FROM);
        $this->assertEquals('perPage',             ListEmailLogRequestPropertyNameList::PER_PAGE);
        $this->assertEquals('page',                ListEmailLogRequestPropertyNameList::PAGE);
        $this->assertEquals('lastComposedEmailId', ListEmailLogRequestPropertyNameList::LAST_COMPOSED_EMAIL_ID);
    }
}
