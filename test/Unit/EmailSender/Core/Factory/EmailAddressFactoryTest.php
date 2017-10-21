<?php

namespace Test\Unit\EmailSender\Core\Factory;

use EmailSender\Core\Factory\EmailAddressFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailAddressFactoryTest
 *
 * @package Test\Unit\EmailSender\Core
 */
class EmailAddressFactoryTest extends TestCase
{
    /**
     * Test buildMailAddressFromString with valid string.
     *
     * @param string      $emailAddressString
     * @param string      $expectedAddress
     * @param string|null $expectedDisplayName
     *
     * @dataProvider providerForTestCreateWithValidValues
     */
    public function testCreateWithValidValues(
        string $emailAddressString,
        string $expectedAddress,
        ?string $expectedDisplayName
    ) {
        $emailAddressFactory = new EmailAddressFactory();

        $emailAddress = $emailAddressFactory->create($emailAddressString);

        $address     = $emailAddress->getAddress();
        $displayName = $emailAddress->getDisplayName();

        if ($displayName) {
            $displayName = $displayName->getValue();
        }

        $this->assertEquals($expectedAddress, $address->getValue());
        $this->assertEquals($expectedDisplayName, $displayName);
    }

    /**
     * Data provider for testCreateWithValidValues.
     *
     * @return array
     */
    public function providerForTestCreateWithValidValues(): array
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
            [
                'test@test.com',
                'test@test.com',
                null,
            ],
        ];
    }
}
