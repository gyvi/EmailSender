<?php

namespace Test\Unit\EmailSender\Email\Domain\Aggregate;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Email\Domain\Aggregate\Email;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailTest
 *
 * @package Test\Unit\EmailSender\Email
 */
class EmailTest extends TestCase
{
    /**
     * Test getFrom method.
     */
    public function testGetFrom()
    {
        $expected               = (new Mockery($this))->getEmailAddressMock('emailaddress', 'name');
        $emailAddressCollection = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $email = new Email(
            $expected,
            $emailAddressCollection,
            $emailAddressCollection,
            $emailAddressCollection,
            (new Mockery($this))->getSubjectMock(''),
            (new Mockery($this))->getBodyMock(''),
            (new Mockery($this))->getEmailAddressMock(),
            (new Mockery($this))->getUnSignedIntegerMock(0)
        );

        $this->assertEquals($expected, $email->getFrom());
    }

    /**
     * Test getTo method.
     */
    public function testGetTo()
    {
        $expected = (new Mockery($this))->getEmailAddressCollectionMock(
            [
                [
                    EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                    EmailAddressPropertyNameList::NAME    => 'name',
                ],
            ]
        );

        $emailAddressCollection = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $email = new Email(
            (new Mockery($this))->getEmailAddressMock(),
            $expected,
            $emailAddressCollection,
            $emailAddressCollection,
            (new Mockery($this))->getSubjectMock(''),
            (new Mockery($this))->getBodyMock(''),
            (new Mockery($this))->getEmailAddressMock(),
            (new Mockery($this))->getUnSignedIntegerMock(0)
        );

        $this->assertEquals($expected, $email->getTo());
    }

    /**
     * Test getCc method.
     */
    public function testGetCc()
    {
        $expected = (new Mockery($this))->getEmailAddressCollectionMock(
            [
                [
                    EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                    EmailAddressPropertyNameList::NAME    => 'name',
                ],
            ]
        );

        $emailAddressCollection = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $email = new Email(
            (new Mockery($this))->getEmailAddressMock(),
            $emailAddressCollection,
            $expected,
            $emailAddressCollection,
            (new Mockery($this))->getSubjectMock(''),
            (new Mockery($this))->getBodyMock(''),
            (new Mockery($this))->getEmailAddressMock(),
            (new Mockery($this))->getUnSignedIntegerMock(0)
        );

        $this->assertEquals($expected, $email->getCc());
    }

    /**
     * Test getBcc method.
     */
    public function testGetBcc()
    {
        $expected = (new Mockery($this))->getEmailAddressCollectionMock(
            [
                [
                    EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                    EmailAddressPropertyNameList::NAME    => 'name',
                ],
            ]
        );

        $emailAddressCollection = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $email = new Email(
            (new Mockery($this))->getEmailAddressMock(),
            $emailAddressCollection,
            $emailAddressCollection,
            $expected,
            (new Mockery($this))->getSubjectMock(''),
            (new Mockery($this))->getBodyMock(''),
            (new Mockery($this))->getEmailAddressMock(),
            (new Mockery($this))->getUnSignedIntegerMock(0)
        );

        $this->assertEquals($expected, $email->getBcc());
    }

    /**
     * Test getSubject method.
     */
    public function testGetSubject()
    {
        $expected = (new Mockery($this))->getSubjectMock('subject');

        $emailAddressCollection = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $email = new Email(
            (new Mockery($this))->getEmailAddressMock(),
            $emailAddressCollection,
            $emailAddressCollection,
            $emailAddressCollection,
            $expected,
            (new Mockery($this))->getBodyMock(''),
            (new Mockery($this))->getEmailAddressMock(),
            (new Mockery($this))->getUnSignedIntegerMock(0)
        );

        $this->assertEquals($expected, $email->getSubject());
    }

    /**
     * Test getBody method.
     */
    public function testGetBody()
    {
        $expected = (new Mockery($this))->getBodyMock('body');

        $emailAddressCollection = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $email = new Email(
            (new Mockery($this))->getEmailAddressMock(),
            $emailAddressCollection,
            $emailAddressCollection,
            $emailAddressCollection,
            (new Mockery($this))->getSubjectMock(''),
            $expected,
            (new Mockery($this))->getEmailAddressMock(),
            (new Mockery($this))->getUnSignedIntegerMock(0)
        );

        $this->assertEquals($expected, $email->getBody());
    }

    /**
     * Test getReplyTo method.
     */
    public function testGetReplyTo()
    {
        $expected               = (new Mockery($this))->getEmailAddressMock('emailaddress', 'name');
        $emailAddressCollection = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $email = new Email(
            (new Mockery($this))->getEmailAddressMock(),
            $emailAddressCollection,
            $emailAddressCollection,
            $emailAddressCollection,
            (new Mockery($this))->getSubjectMock(''),
            (new Mockery($this))->getBodyMock(''),
            $expected,
            (new Mockery($this))->getUnSignedIntegerMock(0)
        );

        $this->assertEquals($expected, $email->getReplyTo());
    }

    /**
     * Test getReplyTo method.
     */
    public function testGetReplyToWithNull()
    {
        $expected               = null;
        $emailAddressCollection = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $email = new Email(
            (new Mockery($this))->getEmailAddressMock(),
            $emailAddressCollection,
            $emailAddressCollection,
            $emailAddressCollection,
            (new Mockery($this))->getSubjectMock(''),
            (new Mockery($this))->getBodyMock(''),
            $expected,
            (new Mockery($this))->getUnSignedIntegerMock(0)
        );

        $this->assertEquals($expected, $email->getReplyTo());
    }

    /**
     * Test getDelay method.
     */
    public function testGetDelay()
    {
        $expected               = (new Mockery($this))->getUnSignedIntegerMock(1);
        $emailAddressCollection = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $email = new Email(
            (new Mockery($this))->getEmailAddressMock(),
            $emailAddressCollection,
            $emailAddressCollection,
            $emailAddressCollection,
            (new Mockery($this))->getSubjectMock(''),
            (new Mockery($this))->getBodyMock(''),
            (new Mockery($this))->getEmailAddressMock(),
            $expected
        );

        $this->assertEquals($expected, $email->getDelay());
    }
}
