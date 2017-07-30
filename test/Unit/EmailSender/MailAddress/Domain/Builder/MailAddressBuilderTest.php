<?php

namespace Test\Unit\EmailSender\MailAddress\Domain\Builder;

use EmailSender\MailAddress\Domain\Builder\MailAddressBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class MailAddressBuilderTest
 *
 * @package Test\Unit\EmailSender\MailAddress\Domain\Builder
 */
class MailAddressBuilderTest extends TestCase
{
    /**
     * Test __construct method.
     */
    public function testConstruct()
    {
        $mailAddressBuilder = new MailAddressBuilder();

        $this->assertInstanceOf(MailAddressBuilder::class, $mailAddressBuilder);
    }

    /**
     * Test buildMailAddressFromString with valid string.
     *
     * @param string      $mailAddressString
     * @param string      $expectedAddress
     * @param string|null $expectedDisplayName
     *
     * @dataProvider providerForTestBuildMailAddressFromStringWithValidValues
     */
    public function testBuildMailAddressFromStringWithValidValues(
        string $mailAddressString,
        string $expectedAddress,
        ?string $expectedDisplayName
    ) {
        $mailAddressBuilder = new MailAddressBuilder();

        $mailAddress = $mailAddressBuilder->buildMailAddressFromString($mailAddressString);

        $address     = $mailAddress->getAddress();
        $displayName = $mailAddress->getDisplayName();

        if ($displayName) {
            $displayName = $displayName->getValue();
        }

        $this->assertEquals($expectedAddress, $address->getValue());
        $this->assertEquals($expectedDisplayName, $displayName);
    }

    /**
     * Data provider for testBuildMailAddressFromStringWithValidValues.
     *
     * @return array
     */
    public function providerForTestBuildMailAddressFromStringWithValidValues()
    {
        return [
            [
                'John Doe <test@test.com>',
                'test@test.com',
                'John Doe',
            ],
            [
                ' "John Doe" <test@test.com>',
                'test@test.com',
                'John Doe',
            ],
            [
                '  <test@test.com>',
                'test@test.com',
                null,
            ],
            [
                '<test@test.com>',
                'test@test.com',
                null,
            ],
            [
                ' test@test.com ',
                'test@test.com',
                null,
            ],
            [
                '"test@test.com"',
                'test@test.com',
                null,
            ],
        ];
    }
}
