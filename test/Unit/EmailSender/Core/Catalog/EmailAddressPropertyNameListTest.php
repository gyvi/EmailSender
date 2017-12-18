<?php

namespace Test\Unit\EmailSender\Core\Catalog;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailAddressPropertyNameListTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class EmailAddressPropertyNameListTest extends TestCase
{
    /**
     * Test List elements.
     */
    public function testListElements()
    {
        $this->assertEquals('address', EmailAddressPropertyNameList::ADDRESS);
        $this->assertEquals('name',    EmailAddressPropertyNameList::NAME);
    }
}
