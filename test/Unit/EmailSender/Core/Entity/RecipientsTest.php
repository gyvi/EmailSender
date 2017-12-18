<?php

namespace Test\Unit\EmailSender\Core\Entity;

use EmailSender\Core\Catalog\RecipientsPropertyNameList;
use EmailSender\Core\Entity\Recipients;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class RecipientsTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class RecipientsTest extends TestCase
{
    /**
     * Test getTo method.
     */
    public function testGetTo()
    {
        $to = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $recipients = new Recipients(
            $to,
            (new Mockery($this))->getEmailAddressCollectionMock([]),
            (new Mockery($this))->getEmailAddressCollectionMock([])
        );

        $this->assertEquals($to, $recipients->getTo());
    }

    /**
     * Test getCC method.
     */
    public function testGetCc()
    {
        $cc = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $recipients = new Recipients(
            (new Mockery($this))->getEmailAddressCollectionMock([]),
            (new Mockery($this))->getEmailAddressCollectionMock([]),
            $cc
        );

        $this->assertEquals($cc, $recipients->getCc());
    }

    /**
     * Test getBCC method.
     */
    public function testGetBcc()
    {
        $bcc = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $recipients = new Recipients(
            (new Mockery($this))->getEmailAddressCollectionMock([]),
            (new Mockery($this))->getEmailAddressCollectionMock([]),
            $bcc
        );

        $this->assertEquals($bcc, $recipients->getBcc());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $to  = (new Mockery($this))->getEmailAddressCollectionMock([]);
        $cc  = (new Mockery($this))->getEmailAddressCollectionMock([]);
        $bcc = (new Mockery($this))->getEmailAddressCollectionMock([]);

        $excepted = [
            RecipientsPropertyNameList::TO  => $to,
            RecipientsPropertyNameList::CC  => $cc,
            RecipientsPropertyNameList::BCC => $bcc,
        ];

        $recipients = new Recipients($to, $cc, $bcc);

        $this->assertEquals($excepted, $recipients->jsonSerialize());
    }
}
