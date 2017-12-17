<?php

namespace Test\Unit\EmailSender\EmailLog\Domain\Aggregate;

use EmailSender\EmailLog\Application\Catalog\EmailLogPropertyNamesList;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailLogTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class EmailLogTest extends TestCase
{
    /**
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     */
    public function getEmailLog()
    {
        return new EmailLog(
            (new Mockery($this))->getUnSignedIntegerMock(1),
            (new Mockery($this))->getEmailAddressMock(),
            (new Mockery($this))->getRecipientsMock(),
            (new Mockery($this))->getSubjectMock(''),
            (new Mockery($this))->getUnSignedIntegerMock(1)
        );
    }

    /**
     * Test setEmailLogId and getEmailLogId methods.
     */
    public function testSetGetEmailLogId()
    {
        $emailLog   = $this->getEmailLog();
        $emailLogId = (new Mockery($this))->getUnSignedIntegerMock(1);

        $emailLog->setEmailLogId($emailLogId);

        $this->assertEquals($emailLogId, $emailLog->getEmailLogId());
    }

    /**
     * Test getComposedEmailId method.
     */
    public function testGetComposedEmailId()
    {
        $emailLog           = $this->getEmailLog();
        $composedEmailLogId = (new Mockery($this))->getUnSignedIntegerMock(1);

        $this->assertEquals($composedEmailLogId, $emailLog->getComposedEmailId());
    }

    /**
     * Test getFrom method.
     */
    public function testGetFrom()
    {
        $emailLog = $this->getEmailLog();
        $from     = (new Mockery($this))->getEmailAddressMock();

        $this->assertEquals($from, $emailLog->getFrom());
    }

    /**
     * Test getRecipients method.
     */
    public function testGetRecipients()
    {
        $emailLog   = $this->getEmailLog();
        $recipients = (new Mockery($this))->getRecipientsMock();

        $this->assertEquals($recipients, $emailLog->getRecipients());
    }

    /**
     * Test getSubject method.
     */
    public function testGetSubject()
    {
        $emailLog = $this->getEmailLog();
        $subject  = (new Mockery($this))->getSubjectMock('');

        $this->assertEquals($subject, $emailLog->getSubject());
    }

    /**
     * Test setLogged and getLogged methods.
     */
    public function testSetGetLogged()
    {
        $emailLog = $this->getEmailLog();
        $logged   = (new Mockery($this))->getDateTimeMock();

        $emailLog->setLogged($logged);

        $this->assertEquals($logged, $emailLog->getLogged());
    }

    /**
     * Test setQueued and getQueued methods.
     */
    public function testSetGetQueued()
    {
        $emailLog = $this->getEmailLog();
        $queued   = (new Mockery($this))->getDateTimeMock();

        $emailLog->setQueued($queued);

        $this->assertEquals($queued, $emailLog->getQueued());
    }

    /**
     * Test setSent and getSent methods.
     */
    public function testSetGetSent()
    {
        $emailLog = $this->getEmailLog();
        $sent     = (new Mockery($this))->getDateTimeMock();

        $emailLog->setSent($sent);

        $this->assertEquals($sent, $emailLog->getSent());
    }

    /**
     * Test getDelay method.
     */
    public function testGetDelay()
    {
        $emailLog = $this->getEmailLog();
        $delay    = (new Mockery($this))->getUnSignedIntegerMock(1);

        $this->assertEquals($delay, $emailLog->getDelay());
    }

    /**
     * Test setStatus and getStatus methods.
     */
    public function testSetGetStatus()
    {
        $emailLog = $this->getEmailLog();
        $status   = (new Mockery($this))->getEmailStatusMock(2);

        $emailLog->setStatus($status);

        $this->assertEquals($status, $emailLog->getStatus());
    }

    /**
     * Test setErrorMessage and getErrorMessage methods.
     */
    public function testSetGetErrorMessage()
    {
        $emailLog     = $this->getEmailLog();
        $errorMessage = (new Mockery($this))->getStringLiteralMock('');

        $emailLog->setErrorMessage($errorMessage);

        $this->assertEquals($errorMessage, $emailLog->getErrorMessage());
    }

    /**
     * Test jsonSerialize method.
     */
    public function testJsonSerialize()
    {
        $expected = [
            EmailLogPropertyNamesList::EMAIL_LOG_ID      => null,
            EmailLogPropertyNamesList::COMPOSED_EMAIL_ID => (new Mockery($this))->getUnSignedIntegerMock(1),
            EmailLogPropertyNamesList::FROM              => (new Mockery($this))->getEmailAddressMock(),
            EmailLogPropertyNamesList::RECIPIENTS        => (new Mockery($this))->getRecipientsMock(),
            EmailLogPropertyNamesList::SUBJECT           => (new Mockery($this))->getSubjectMock(''),
            EmailLogPropertyNamesList::LOGGED            => null,
            EmailLogPropertyNamesList::QUEUED            => null,
            EmailLogPropertyNamesList::SENT              => null,
            EmailLogPropertyNamesList::DELAY             => (new Mockery($this))->getUnSignedIntegerMock(1),
            EmailLogPropertyNamesList::STATUS            => null,
            EmailLogPropertyNamesList::ERROR_MESSAGE     => null,
        ];

        $emailLog = new EmailLog(
            $expected[EmailLogPropertyNamesList::COMPOSED_EMAIL_ID],
            $expected[EmailLogPropertyNamesList::FROM],
            $expected[EmailLogPropertyNamesList::RECIPIENTS],
            $expected[EmailLogPropertyNamesList::SUBJECT],
            $expected[EmailLogPropertyNamesList::DELAY]
        );

        $this->assertEquals($expected, $emailLog->jsonSerialize());
    }
}
