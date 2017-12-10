<?php

namespace Test\Unit\EmailSender\ComposedEmail\Domain\Aggregate;

use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class ComposedEmailTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class ComposedEmailTest extends TestCase
{
    /**
     * Test getFrom method.
     */
    public function testGetFrom()
    {
        $expected      = (new Mockery($this))->getEmailAddressMock('emailaddress', 'name');
        $composedEmail = new ComposedEmail(
            $expected,
            (new Mockery($this))->getRecipientsMock(),
            (new Mockery($this))->getStringLiteralMock('email')
        );

        $this->assertEquals($expected, $composedEmail->getFrom());
    }

    /**
     * Test getRecipients method.
     */
    public function testGetRecipients()
    {
        $expected      = (new Mockery($this))->getRecipientsMock();
        $composedEmail = new ComposedEmail(
            (new Mockery($this))->getEmailAddressMock('emailaddress', 'name'),
            $expected,
            (new Mockery($this))->getStringLiteralMock('email')
        );

        $this->assertEquals($expected, $composedEmail->getRecipients());
    }

    /**
     * Test getEmail method.
     */
    public function testGetEmail()
    {
        $expected      = (new Mockery($this))->getStringLiteralMock('email');
        $composedEmail = new ComposedEmail(
            (new Mockery($this))->getEmailAddressMock('emailaddress', 'name'),
            (new Mockery($this))->getRecipientsMock(),
            $expected
        );

        $this->assertEquals($expected, $composedEmail->getEmail());
    }

    /**
     * Test setComposedEmailId and getComposedEmailId methods.
     */
    public function testSetGetComposedEmailId()
    {
        $expected      = (new Mockery($this))->getUnSignedIntegerMock(1);
        $composedEmail = new ComposedEmail(
            (new Mockery($this))->getEmailAddressMock('emailaddress', 'name'),
            (new Mockery($this))->getRecipientsMock(),
            (new Mockery($this))->getStringLiteralMock('email')
        );

        $composedEmail->setComposedEmailId($expected);

        $this->assertEquals($expected, $composedEmail->getComposedEmailId());
    }
}
