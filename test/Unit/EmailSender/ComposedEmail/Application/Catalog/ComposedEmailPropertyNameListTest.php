<?php

namespace Test\Unit\EmailSender\ComposedEmail\Application\Catalog;

use EmailSender\ComposedEmail\Application\Catalog\ComposedEmailPropertyNameList;
use PHPUnit\Framework\TestCase;

/**
 * Class ComposedEmailPropertyNameListTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class ComposedEmailPropertyNameListTest extends TestCase
{
    /**
     * Test ComposedEmailPropertyNameList elements.
     */
    public function testListElements()
    {
        $this->assertEquals('composedEmailId', ComposedEmailPropertyNameList::COMPOSED_EMAIL_ID);
        $this->assertEquals('from',            ComposedEmailPropertyNameList::FROM);
        $this->assertEquals('recipients',      ComposedEmailPropertyNameList::RECIPIENTS);
        $this->assertEquals('email',           ComposedEmailPropertyNameList::EMAIL);
    }
}
