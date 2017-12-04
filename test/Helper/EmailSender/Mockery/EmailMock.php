<?php

namespace Test\Helper\EmailSender\Mockery;

use EmailSender\Email\Domain\Aggregate\Email;

/**
 * Trait EmailMock
 *
 * @package Test\Helper
 */
trait EmailMock
{
    /**
     * @return \EmailSender\Email\Domain\Aggregate\Email|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getEmailMock(): Email
    {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $emailMock = $testCase->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $emailMock;
    }
}
