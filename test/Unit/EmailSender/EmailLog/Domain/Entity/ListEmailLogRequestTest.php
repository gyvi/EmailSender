<?php

namespace Test\Unit\EmailSender\EmailLog\Domain\Entity;

use EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class ListEmailLogRequestTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class ListEmailLogRequestTest extends TestCase
{
    /**
     * Test setFrom and getFrom methods.
     */
    public function testSetGetFrom()
    {
        $from = (new Mockery($this))->getEmailAddressMock();

        $listEmailLofRequest = new ListEmailLogRequest();

        $listEmailLofRequest->setFrom($from);

        $this->assertEquals($from, $listEmailLofRequest->getFrom());
    }

    /**
     * Test setFrom and getFrom methods with null value.
     */
    public function testSetGetFromWithNull()
    {
        $listEmailLofRequest = new ListEmailLogRequest();

        $listEmailLofRequest->setFrom(null);

        $this->assertNull($listEmailLofRequest->getFrom());
    }

    /**
     * Test setPerPage and getPerPage methods.
     */
    public function testGetPerPage()
    {
        $perPage = (new Mockery($this))->getUnSignedIntegerMock(1);

        $listEmailLofRequest = new ListEmailLogRequest();

        $listEmailLofRequest->setPerPage($perPage);

        $this->assertEquals($perPage, $listEmailLofRequest->getPerPage());
    }

    /**
     * Test setPerPage and getPerPage methods with null value.
     */
    public function testGetPerPageWithNull()
    {
        $listEmailLofRequest = new ListEmailLogRequest();

        $listEmailLofRequest->setPerPage(null);

        $this->assertNull($listEmailLofRequest->getPerPage());
    }

    /**
     * Test setPage and getPage methods.
     */
    public function testSetGetPage()
    {
        $page = (new Mockery($this))->getUnSignedIntegerMock(1);

        $listEmailLofRequest = new ListEmailLogRequest();

        $listEmailLofRequest->setPage($page);

        $this->assertEquals($page, $listEmailLofRequest->getPage());
    }

    /**
     * Test setPage and getPage methods with null value.
     */
    public function testSetGetPageWithNull()
    {
        $listEmailLofRequest = new ListEmailLogRequest();

        $listEmailLofRequest->setPage(null);

        $this->assertNull($listEmailLofRequest->getPage());
    }

    /**
     * Test setLastComposedEmailId and getLastComposedEmailId methods.
     */
    public function testSetGetLastComposedEmailId()
    {
        $lastComposedEmailId = (new Mockery($this))->getUnSignedIntegerMock(1);

        $listEmailLofRequest = new ListEmailLogRequest();

        $listEmailLofRequest->setLastComposedEmailId($lastComposedEmailId);

        $this->assertEquals($lastComposedEmailId, $listEmailLofRequest->getLastComposedEmailId());
    }

    /**
     * Test setLastComposedEmailId and getLastComposedEmailId methods with null value.
     */
    public function testSetGetLastComposedEmailIdWithNull()
    {
        $listEmailLofRequest = new ListEmailLogRequest();

        $listEmailLofRequest->setLastComposedEmailId(null);

        $this->assertNull($listEmailLofRequest->getLastComposedEmailId());
    }
}
