<?php

namespace Test\Helper\EmailSender\Mockery;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;

/**
 * Trait EmailLogMock
 *
 * @package Test\Helper
 */
trait EmailLogMock
{
    /**
     * @param int|null    $emailLogId
     * @param int|null    $composedEmailId
     * @param array|null  $from
     * @param array|null  $recipients
     * @param null|string $subject
     * @param null|string $logged
     * @param null|string $queued
     * @param null|string $sent
     * @param int|null    $delay
     * @param int|null    $status
     * @param null|string $errorMessage
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getEmailLogMock(
        ?int $emailLogId = null,
        ?int $composedEmailId = null,
        ?array $from = null,
        ?array $recipients = null,
        ?string $subject = null,
        ?string $logged = null,
        ?string $queued = null,
        ?string $sent = null,
        ?int $delay = null,
        ?int $status = null,
        ?string $errorMessage = null
    ): EmailLog {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $emailLogMock = $testCase->getMockBuilder(EmailLog::class)
            ->disableOriginalConstructor()
            ->getMock();

        if ($emailLogId !== null) {
            $emailLogMock->expects($testCase->any())
                ->method('getEmailLogId')
                ->willReturn(
                    $this->getUnSignedIntegerMock($emailLogId)
                );
        }

        if ($composedEmailId !== null) {
            $emailLogMock->expects($testCase->any())
                ->method('getComposedEmailId')
                ->willReturn(
                    $this->getUnSignedIntegerMock($composedEmailId)
                );
        }

        if ($from) {
            $emailLogMock->expects($testCase->any())
                ->method('getFrom')
                ->willReturn(
                    $this->getEmailAddressMock(
                        $from[EmailAddressPropertyNameList::ADDRESS],
                        $from[EmailAddressPropertyNameList::NAME]
                    )
                );
        }

        if ($recipients) {
            $emailLogMock->expects($testCase->any())
                ->method('getRecipients')
                ->willReturn(
                    $this->getRecipientsMock($recipients)
                );
        }

        if ($subject) {
            $emailLogMock->expects($testCase->any())
                ->method('getSubject')
                ->willReturn(
                    $this->getSubjectMock($subject)
                );
        }

        if ($logged) {
            $emailLogMock->expects($testCase->any())
                ->method('getLogged')
                ->willReturn(
                    $this->getDateTimeMock()
                );
        }

        if ($queued) {
            $emailLogMock->expects($testCase->any())
                ->method('getLogged')
                ->willReturn(
                    $this->getDateTimeMock()
                );
        }

        if ($sent) {
            $emailLogMock->expects($testCase->any())
                ->method('getLogged')
                ->willReturn(
                    $this->getDateTimeMock()
                );
        }

        if ($delay !== null) {
            $emailLogMock->expects($testCase->any())
                ->method('getDelay')
                ->willReturn(
                    $this->getUnSignedIntegerMock($delay)
                );
        }

        if ($status !== null) {
            $emailLogMock->expects($testCase->any())
                ->method('getStatus')
                ->willReturn(
                    $this->getSignedIntegerMock($status)
                );
        }

        if ($errorMessage !== null) {
            $emailLogMock->expects($testCase->any())
                ->method('getErrorMessage')
                ->willReturn(
                    $this->getStringLiteralMock($errorMessage)
                );
        }

        return $emailLogMock;
    }
}
