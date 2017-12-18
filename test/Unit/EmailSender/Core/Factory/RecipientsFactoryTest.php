<?php

namespace Test\Unit\EmailSender\Core\Factory;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Catalog\RecipientsPropertyNameList;
use EmailSender\Core\Entity\Recipients;
use EmailSender\Core\Factory\EmailAddressCollectionFactory;
use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Email\Application\Catalog\EmailPropertyNameList;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class RecipientsFactoryTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class RecipientsFactoryTest extends TestCase
{
    /**
     * Test create method.
     *
     * @param array $emailArray
     * @param int   $toCount
     * @param int   $ccCount
     * @param int   $bccCount
     *
     * @dataProvider providerForTestCreate
     */
    public function testCreate(array $emailArray, int $toCount, int $ccCount, int $bccCount)
    {
        $email = (new Mockery($this))->getEmailMock(
            null,
            $emailArray[EmailPropertyNameList::TO],
            $emailArray[EmailPropertyNameList::CC],
            $emailArray[EmailPropertyNameList::BCC]
        );

        /** @var \EmailSender\Core\Factory\EmailAddressCollectionFactory|\PHPUnit_Framework_MockObject_MockObject $emailAddressCollectionFactory */
        $emailAddressCollectionFactory = $this->getMockBuilder(EmailAddressCollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $recipientsFactory = new RecipientsFactory($emailAddressCollectionFactory);
        $recipients        = $recipientsFactory->create($email);

        $this->assertInstanceOf(Recipients::class, $recipients);
        $this->assertEquals($toCount, $recipients->getTo()->count());
        $this->assertEquals($ccCount, $recipients->getCc()->count());
        $this->assertEquals($bccCount, $recipients->getBcc()->count());
    }

    /**
     * Test createFromArray method.
     *
     * @param array $recipientsArray
     *
     * @dataProvider providerForTestCreateFromArray
     */
    public function testCreateFromArray(array $recipientsArray)
    {
        /** @var \EmailSender\Core\Factory\EmailAddressCollectionFactory|\PHPUnit_Framework_MockObject_MockObject $emailAddressCollectionFactory */
        $emailAddressCollectionFactory = $this->getMockBuilder(EmailAddressCollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $emailAddressCollectionFactory->expects($this->any())
            ->method('createFromArray')
            ->willReturn((new Mockery($this))->getEmailAddressCollectionMock([]));

        $recipientsFactory = new RecipientsFactory($emailAddressCollectionFactory);
        $recipients        = $recipientsFactory->createFromArray($recipientsArray);

        $this->assertInstanceOf(Recipients::class, $recipients);
    }

    /**
     * Data provider for testCreate method.
     *
     * @return array
     */
    public function providerForTestCreate(): array
    {
        return [
            [
                [
                    EmailPropertyNameList::TO => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                    ],
                    EmailPropertyNameList::CC => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress2',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                    ],
                    EmailPropertyNameList::BCC => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress3',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                    ],
                ],
                1,
                1,
                1,
            ],
            [
                [
                    EmailPropertyNameList::TO => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                    ],
                    EmailPropertyNameList::CC => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                    ],
                    EmailPropertyNameList::BCC => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                    ],
                ],
                1,
                0,
                0,
            ],
            [
                [
                    EmailPropertyNameList::TO => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                    ],
                    EmailPropertyNameList::CC => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress1',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                    ],
                    EmailPropertyNameList::BCC => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress2',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress1',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                    ],
                ],
                1,
                1,
                1,
            ],
            [
                [
                    EmailPropertyNameList::TO => [
                    ],
                    EmailPropertyNameList::CC => [
                    ],
                    EmailPropertyNameList::BCC => [
                    ],
                ],
                0,
                0,
                0,
            ],
        ];
    }

    /**
     * Data provider for testCreateFromArray method.
     *
     * @return array
     */
    public function providerForTestCreateFromArray(): array
    {
        return [
            [
                [
                    RecipientsPropertyNameList::TO  => [],
                    RecipientsPropertyNameList::CC  => [],
                    RecipientsPropertyNameList::BCC => [],
                ],
            ],
            [
                [
                    RecipientsPropertyNameList::TO  => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                    ],
                    RecipientsPropertyNameList::CC  => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                    ],
                    RecipientsPropertyNameList::BCC => [
                        [
                            EmailAddressPropertyNameList::ADDRESS => 'emailaddress',
                            EmailAddressPropertyNameList::NAME    => null,
                        ],
                    ],
                ],
            ],
        ];
    }
}
