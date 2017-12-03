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
     * @param string|null $expectedName
     *
     * @dataProvider providerForTestCreateWithValidValues
     */
    public function testCreateWithValidValues(
        string $emailAddressString,
        string $expectedAddress,
        ?string $expectedName
    ) {
        $emailAddressFactory = new EmailAddressFactory();

        $emailAddress = $emailAddressFactory->create($emailAddressString);

        $address = $emailAddress->getAddress();
        $name    = $emailAddress->getName();

        if ($name) {
            $name = $name->getValue();
        }

        $this->assertEquals($expectedAddress, $address->getValue());
        $this->assertEquals($expectedName, $name);
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
