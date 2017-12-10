<?php

namespace Test\Helper\EmailSender\Mockery;

use EmailSender\Core\Entity\Recipients;

/**
 * Trait RecipientsMock
 *
 * @package Test\Helper
 */
trait RecipientsMock
{
    public function getRecipientsMock(
        ?array $to = null,
        ?array $cc = null,
        ?array $bcc = null
    ): Recipients {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \EmailSender\Core\Entity\Recipients|\PHPUnit_Framework_MockObject_MockObject $recipientsMock */
        $recipientsMock = $testCase->getMockBuilder(Recipients::class)
            ->disableOriginalConstructor()
            ->getMock();

        if ($to) {
            $recipientsMock->expects($testCase->any())
                ->method('getTo')
                ->willReturn(
                    $this->getEmailAddressCollectionMock($to)
                );
        }

        if ($cc) {
            $recipientsMock->expects($testCase->any())
                ->method('getCc')
                ->willReturn(
                    $this->getEmailAddressCollectionMock($cc)
                );
        }

        if ($bcc) {
            $recipientsMock->expects($testCase->any())
                ->method('getBcc')
                ->willReturn(
                    $this->getEmailAddressCollectionMock($bcc)
                );
        }

        return $recipientsMock;
    }
}
