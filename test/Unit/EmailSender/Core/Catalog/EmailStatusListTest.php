<?php

namespace Test\Unit\EmailSender\Core\Catalog;

use EmailSender\Core\Catalog\EmailStatusList;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailStatusListTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class EmailStatusListTest extends TestCase
{
    /**
     * Test List elements.
     */
    public function testListElements()
    {
        $this->assertEquals('-1', EmailStatusList::ERROR);
        $this->assertEquals('0',  EmailStatusList::LOGGED);
        $this->assertEquals('1',  EmailStatusList::QUEUED);
        $this->assertEquals('2',  EmailStatusList::SENT);
    }
}
