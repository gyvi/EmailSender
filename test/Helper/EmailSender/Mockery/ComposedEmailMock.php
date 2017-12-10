<?php

namespace Test\Helper\EmailSender\Mockery;

use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Catalog\RecipientsPropertyNameList;

/**
 * Trait ComposedEmailMock
 *
 * @package Test\Helper
 */
trait ComposedEmailMock
{
    /**
     * @param int|null    $composedEmailId
     * @param array|null  $from
     * @param array|null  $recipients
     * @param null|string $email
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getComposedEmailMock(
        ?int $composedEmailId = null,
        ?array $from = null,
        ?array $recipients = null,
        ?string $email = null
    ): ComposedEmail {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $composedEmailMock = $testCase->getMockBuilder(ComposedEmail::class)
            ->disableOriginalConstructor()
            ->getMock();

        if ($composedEmailId !== null) {
            $composedEmailMock->expects($testCase->any())
                ->method('getComposedEmailId')
                ->willReturn(
                    $this->getUnSignedIntegerMock($composedEmailId)
                );
        }

        if ($from) {
            $composedEmailMock->expects($testCase->any())
                ->method('getFrom')
                ->willReturn(
                    $this->getEmailAddressMock(
                        $from[EmailAddressPropertyNameList::ADDRESS],
                        $from[EmailAddressPropertyNameList::NAME]
                    )
                );
        }

        if ($recipients) {
            $composedEmailMock->expects($testCase->any())
                ->method('getRecipients')
                ->willReturn(
                    $this->getRecipientsMock(
                        $recipients[RecipientsPropertyNameList::TO],
                        $recipients[RecipientsPropertyNameList::CC],
                        $recipients[RecipientsPropertyNameList::BCC]
                    )
                );
        }

        if ($email) {
            $composedEmailMock->expects($testCase->any())
                ->method('getEmail')
                ->willReturn(
                    $this->getStringLiteralMock($email)
                );
        }

        return $composedEmailMock;
    }
}
