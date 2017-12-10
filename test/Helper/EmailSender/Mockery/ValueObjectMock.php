<?php

namespace Test\Helper\EmailSender\Mockery;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\SignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteralLimit;
use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\EmailAddress;
use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\Core\ValueObject\Name;
use EmailSender\Core\ValueObject\Subject;
use EmailSender\Email\Domain\ValueObject\Body;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Trait ValueObjectMock
 *
 * @package Test\Helper
 */
trait ValueObjectMock
{
    /**
     * @param null|string $value
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\EmailSender\Core\ValueObject\Name
     */
    public function getNameMock(?string $value): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getValueObjectMock(Name::class, $value);
    }

    /**
     * @param null|string $value
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\EmailSender\Core\ValueObject\Address
     */
    public function getAddressMock(?string $value): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getValueObjectMock(Address::class, $value);
    }

    /**
     * @param null|string $address
     * @param null|string $name
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\EmailSender\Core\ValueObject\EmailAddress
     */
    public function getEmailAddressMock(
        ?string $address = null,
        ?string $name = null
    ): PHPUnit_Framework_MockObject_MockObject {
        /** @var \PHPUnit\Framework\TestCase $testCase */
        $testCase = $this->testCase;

        /** @var \PHPUnit_Framework_MockObject_MockObject $valueObjectMock */
        $emailAddressMock = $testCase->getMockBuilder(EmailAddress::class)
            ->disableOriginalConstructor()
            ->getMock();

        $emailAddressMock->expects($testCase->any())
            ->method('getName')
            ->willReturn($this->getNameMock($name));

        $emailAddressMock->expects($testCase->any())
            ->method('getAddress')
            ->willReturn($this->getAddressMock($address));

        return $emailAddressMock;
    }

    /**
     * @param string $value
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\EmailSender\Core\ValueObject\Subject
     */
    public function getSubjectMock(string $value): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getValueObjectMock(Subject::class, $value);
    }

    /**
     * @param string $value
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\EmailSender\Email\Domain\ValueObject\Body
     */
    public function getBodyMock(string $value): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getValueObjectMock(Body::class, $value);
    }

    /**
     * @param string $value
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\EmailSender\Core\ValueObject\EmailStatus
     */
    public function getEmailStatusMock(string $value): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getValueObjectMock(EmailStatus::class, $value);
    }

    /**
     * @param int $value
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\EmailSender\Core\Scalar\Application\ValueObject\Numeric\SignedInteger
     */
    public function getSignedIntegerMock(int $value): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getValueObjectMock(SignedInteger::class, $value);
    }

    /**
     * @param int $value
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnSignedInteger
     */
    public function getUnSignedIntegerMock(int $value): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getValueObjectMock(UnsignedInteger::class, $value);
    }

    /**
     * @param string $value
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral
     */
    public function getStringLiteralMock(string $value): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getValueObjectMock(StringLiteral::class, $value);
    }

    /**
     * @param string $value
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteralLimit
     */
    public function getStringLiteralLimitMock(string $value): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getValueObjectMock(StringLiteralLimit::class, $value);
    }
}
