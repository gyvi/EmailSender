<?php

namespace Test\Helper\EmailSender\Mockery;

use EmailSender\EmailLog\Domain\Aggregate\EmailLog;

/**
 * Trait EmailLogMock
 *
 * @package Test\Helper
 */
trait EmailLogMock
{
    /**
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getEmailLogMock(): EmailLog {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $emailLogMock = $testCase->getMockBuilder(EmailLog::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $emailLogMock;
    }
}
