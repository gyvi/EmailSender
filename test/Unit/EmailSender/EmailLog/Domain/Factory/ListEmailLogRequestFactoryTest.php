<?php

namespace Test\Unit\EmailSender\EmailLog\Domain\Factory;

use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\ValueObject\EmailAddress;
use EmailSender\EmailLog\Application\Catalog\ListEmailLogRequestPropertyNameList;
use EmailSender\EmailLog\Domain\Entity\ListEmailLogRequest;
use EmailSender\EmailLog\Domain\Factory\ListEmailLogRequestFactory;
use PHPUnit\Framework\TestCase;
use Test\Helper\EmailSender\Mockery;

/**
 * Class ListEmailLogRequestFactoryTest
 *
 * @package Test\Unit\EmailSender\EmailLog
 */
class ListEmailLogRequestFactoryTest extends TestCase
{
    /**
     * @var \EmailSender\Core\Factory\EmailAddressFactory|\PHPUnit_Framework_MockObject_MockObject $emailAddressFactory
     */
    private $emailAddressFactory;

    /**
     * Test's setup method.
     */
    public function setUp()
    {
        $this->emailAddressFactory = $this->getMockBuilder(EmailAddressFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Test create method.
     *
     * @param array $listEmailLogRequestArray
     *
     * @dataProvider providerForTestCreateWithValidValues
     */
    public function testCreateWithValidValues(array $listEmailLogRequestArray)
    {
        if (!empty($listEmailLogRequestArray[ListEmailLogRequestPropertyNameList::FROM])) {
            $this->emailAddressFactory->expects($this->once())
                ->method('create')
                ->willReturn((new Mockery($this))->getEmailAddressMock());
        }

        $listEmailLogRequestFactory = new ListEmailLogRequestFactory($this->emailAddressFactory);

        $listEmailLogRequest = $listEmailLogRequestFactory->create($listEmailLogRequestArray);

        $this->assertInstanceOf(ListEmailLogRequest::class, $listEmailLogRequest);

        if (!empty($listEmailLogRequestArray[ListEmailLogRequestPropertyNameList::FROM])) {
            $this->assertInstanceOf(EmailAddress::class, $listEmailLogRequest->getFrom());
        }

        if (!empty($listEmailLogRequestArray[ListEmailLogRequestPropertyNameList::PER_PAGE])) {
            $this->assertInstanceOf(UnsignedInteger::class, $listEmailLogRequest->getPerPage());
        }

        if (!empty($listEmailLogRequestArray[ListEmailLogRequestPropertyNameList::PAGE])) {
            $this->assertInstanceOf(UnsignedInteger::class, $listEmailLogRequest->getPage());
        }

        if (!empty($listEmailLogRequestArray[ListEmailLogRequestPropertyNameList::LAST_COMPOSED_EMAIL_ID])) {
            $this->assertInstanceOf(UnsignedInteger::class, $listEmailLogRequest->getLastComposedEmailId());
        }
    }

    /**
     * Test create with invalid from value.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Wrong property: 'from'
     */
    public function testCreateWithInvalidFrom()
    {
        $listEmailLogRequestArray = [
            ListEmailLogRequestPropertyNameList::FROM => 'test@test.com'
        ];

        $this->emailAddressFactory->expects($this->once())
            ->method('create')
            ->willThrowException(new ValueObjectException('error message'));

        $listEmailLogRequestFactory = new ListEmailLogRequestFactory($this->emailAddressFactory);

        $listEmailLogRequestFactory->create($listEmailLogRequestArray);
    }

    /**
     * Test create with invalid perPage value.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Wrong property: 'perPage'
     */
    public function testCreateWithInvalidPerPage()
    {
        $listEmailLogRequestArray = [
            ListEmailLogRequestPropertyNameList::PER_PAGE => 'something'
        ];

        $listEmailLogRequestFactory = new ListEmailLogRequestFactory($this->emailAddressFactory);

        $listEmailLogRequestFactory->create($listEmailLogRequestArray);
    }

    /**
     * Test create with invalid page value.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Wrong property: 'page'
     */
    public function testCreateWithInvalidPage()
    {
        $listEmailLogRequestArray = [
            ListEmailLogRequestPropertyNameList::PAGE => 'something'
        ];

        $listEmailLogRequestFactory = new ListEmailLogRequestFactory($this->emailAddressFactory);

        $listEmailLogRequestFactory->create($listEmailLogRequestArray);
    }

    /**
     * Test create with invalid lastComposedEmailId value.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Wrong property: 'lastComposedEmailId'
     */
    public function testCreateWithInvalidLastComposedEmailId()
    {
        $listEmailLogRequestArray = [
            ListEmailLogRequestPropertyNameList::LAST_COMPOSED_EMAIL_ID => 'something'
        ];

        $listEmailLogRequestFactory = new ListEmailLogRequestFactory($this->emailAddressFactory);

        $listEmailLogRequestFactory->create($listEmailLogRequestArray);
    }

    /**
     * Data provider for testCreateWithValidValues method.
     *
     * @return array
     */
    public function providerForTestCreateWithValidValues(): array
    {
        return [
            [
                [
                    ListEmailLogRequestPropertyNameList::FROM => 'test@test.com'
                ],
            ],
            [
                [
                    ListEmailLogRequestPropertyNameList::PER_PAGE => '1'
                ],
            ],
            [
                [
                    ListEmailLogRequestPropertyNameList::PAGE => '1'
                ],
            ],
            [
                [
                    ListEmailLogRequestPropertyNameList::LAST_COMPOSED_EMAIL_ID => '1'
                ],
            ],
            [
                [
                    ListEmailLogRequestPropertyNameList::FROM                   => 'test@test.com',
                    ListEmailLogRequestPropertyNameList::PER_PAGE               => '1',
                    ListEmailLogRequestPropertyNameList::PAGE                   => '1',
                    ListEmailLogRequestPropertyNameList::LAST_COMPOSED_EMAIL_ID => '1',
                ],
            ],
        ];
    }
}
