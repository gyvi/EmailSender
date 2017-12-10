<?php

namespace Test\Unit\EmailSender\ComposedEmail\Infrastructure\Service;

use EmailSender\ComposedEmail\Infrastructure\Service\EmailComposer;
use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Email\Application\Catalog\EmailPropertyNameList;
use PHPUnit\Framework\TestCase;
use PHPMailer;
use Test\Helper\EmailSender\Mockery;

/**
 * Class EmailComposerTest
 *
 * @package Test\Unit\EmailSender\ComposedEmail
 */
class EmailComposerTest extends TestCase
{
    /**
     * Test compose method.
     *
     * @param array  $emailProperties
     * @param string $expected
     *
     * @dataProvider providerForTestCompose
     */
    public function testCompose(array $emailProperties, $expected)
    {
        /** @var \PHPMailer|\PHPUnit_Framework_MockObject_MockObject $phpMailer */
        $phpMailer = $this->getMockBuilder(PHPMailer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phpMailer->expects($this->once())
            ->method('setFrom')
            ->willReturn(null);

        $phpMailer->expects($this->once())
            ->method('isHtml')
            ->willReturn(null);

        $phpMailer->expects($this->once())
            ->method('preSend')
            ->willReturn(null);

        $phpMailer->expects($this->exactly(count($emailProperties[EmailPropertyNameList::TO])))
            ->method('addAddress')
            ->willReturn(null);

        $phpMailer->expects($this->exactly(count($emailProperties[EmailPropertyNameList::CC])))
            ->method('addCC')
            ->willReturn(null);

        $phpMailer->expects($this->exactly(count($emailProperties[EmailPropertyNameList::BCC])))
            ->method('addBCC')
            ->willReturn(null);

        $phpMailer->expects($this->once())
            ->method('getSentMIMEMessage')
            ->willReturn($expected);

        if (!empty($emailProperties[EmailPropertyNameList::REPLY_TO])) {
            $phpMailer->expects($this->once())
                ->method('addReplyTo')
                ->willReturn(null);
        }

        $email = (new Mockery($this))->getEmailMock(
            $emailProperties[EmailPropertyNameList::FROM],
            $emailProperties[EmailPropertyNameList::TO],
            $emailProperties[EmailPropertyNameList::CC],
            $emailProperties[EmailPropertyNameList::BCC],
            $emailProperties[EmailPropertyNameList::SUBJECT],
            $emailProperties[EmailPropertyNameList::BODY],
            $emailProperties[EmailPropertyNameList::REPLY_TO],
            $emailProperties[EmailPropertyNameList::DELAY]
        );

        $emailComposer = new EmailComposer($phpMailer);

        $this->assertEquals(new StringLiteral($expected), $emailComposer->compose($email));
    }

    /**
     * Data provider for testCompose method.
     * @return array
     */
    public function providerForTestCompose()
    {
        return [
            [
                [
                    EmailPropertyNameList::TO => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => 'name',
                        ],
                    ],
                    EmailPropertyNameList::CC => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => 'name',
                        ],
                    ],
                    EmailPropertyNameList::BCC => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => 'name',
                        ]
                    ],
                    EmailPropertyNameList::REPLY_TO => null,
                    EmailPropertyNameList::FROM => [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                        EmailAddressPropertyNameList::NAME    => 'name',
                    ],
                    EmailPropertyNameList::DELAY   => 1,
                    EmailPropertyNameList::SUBJECT => 'subject',
                    EmailPropertyNameList::BODY    => 'body',
                ],
                'composedmailtext'
            ],

            [
                [
                    EmailPropertyNameList::TO => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => 'name',
                        ],
                    ],
                    EmailPropertyNameList::CC => [
                    ],
                    EmailPropertyNameList::BCC => [
                    ],
                    EmailPropertyNameList::REPLY_TO => [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                        EmailAddressPropertyNameList::NAME    => 'name',
                    ],
                    EmailPropertyNameList::FROM => [
                        EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                        EmailAddressPropertyNameList::NAME    => 'name',
                    ],
                    EmailPropertyNameList::DELAY   => 1,
                    EmailPropertyNameList::SUBJECT => 'subject',
                    EmailPropertyNameList::BODY    => 'body',
                ],
                'composedmailtext'
            ],
        ];
    }
}
