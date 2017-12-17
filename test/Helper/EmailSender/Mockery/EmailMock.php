<?php

namespace Test\Helper\EmailSender\Mockery;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Email\Domain\Aggregate\Email;

/**
 * Trait EmailMock
 *
 * @package Test\Helper
 */
trait EmailMock
{
    /**
     * @param array|null  $from
     * @param array|null  $to
     * @param array|null  $cc
     * @param array|null  $bcc
     * @param null|string $subject
     * @param null|string $body
     * @param array|null  $replyTo
     * @param int|null    $delay
     *
     * @return \EmailSender\Email\Domain\Aggregate\Email|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getEmailMock(
        ?array $from = null,
        ?array $to = null,
        ?array $cc = null,
        ?array $bcc = null,
        ?string $subject = null,
        ?string $body = null,
        ?array $replyTo = null,
        ?int $delay = null
    ): Email {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \EmailSender\Email\Domain\Aggregate\Email|\PHPUnit_Framework_MockObject_MockObject $email */
        $email = $testCase->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();

        if ($from) {
            $email->expects($testCase->any())
                ->method('getFrom')
                ->willReturn(
                    $this->getEmailAddressMock(
                        $from[EmailAddressPropertyNameList::ADDRESS],
                        $from[EmailAddressPropertyNameList::NAME]
                    )
                );
        }

        if ($to) {
            $email->expects($testCase->any())
                ->method('getTo')
                ->willReturn(
                    $this->getEmailAddressCollectionMock($to)
                );
        }

        if ($cc) {
            $email->expects($testCase->any())
                ->method('getCc')
                ->willReturn(
                    $this->getEmailAddressCollectionMock($cc)
                );
        }

        if ($bcc) {
            $email->expects($testCase->any())
                ->method('getBcc')
                ->willReturn(
                    $this->getEmailAddressCollectionMock($bcc)
                );
        }

        if ($subject) {
            $email->expects($testCase->any())
                ->method('getSubject')
                ->willReturn(
                    $this->getSubjectMock($subject)
                );
        }

        if ($body) {
            $email->expects($testCase->any())
                ->method('getBody')
                ->willReturn(
                    $this->getBodyMock($body)
                );
        }

        if ($replyTo) {
            $email->expects($testCase->any())
                ->method('getReplyTo')
                ->willReturn(
                    $this->getEmailAddressMock(
                        $replyTo[EmailAddressPropertyNameList::ADDRESS],
                        $replyTo[EmailAddressPropertyNameList::NAME]
                    )
                );
        }

        if ($delay !== null) {
            $email->expects($testCase->any())
                ->method('getDelay')
                ->willReturn(
                    $this->getUnSignedIntegerMock($delay)
                );
        }

        return $email;
    }
}
