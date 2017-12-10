<?php

namespace Test\Unit\EmailSender\ComposedEmail\Infrastructure\Persistence;

use EmailSender\ComposedEmail\Infrastructure\Persistence\ComposedEmailRepositoryFieldList;
use PHPUnit\Framework\TestCase;

/**
 * Class ComposedEmailRepositoryFieldListTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class ComposedEmailRepositoryFieldListTest extends TestCase
{
    /**
     * Test ComposedEmailRepositoryFieldList elements.
     */
    public function testListElements()
    {
        $this->assertEquals('composedEmailId', ComposedEmailRepositoryFieldList::COMPOSED_EMAIL_ID);
        $this->assertEquals('from',            ComposedEmailRepositoryFieldList::FROM);
        $this->assertEquals('recipients',      ComposedEmailRepositoryFieldList::RECIPIENTS);
        $this->assertEquals('email',           ComposedEmailRepositoryFieldList::EMAIL);
    }
}
