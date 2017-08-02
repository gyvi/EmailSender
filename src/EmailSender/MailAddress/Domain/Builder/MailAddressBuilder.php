<?php

namespace EmailSender\MailAddress\Domain\Builder;

use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\DisplayName;

/**
 * Class MailAddressBuilder
 *
 * @package EmailSender\MailAddress
 */
class MailAddressBuilder
{
    /**
     * Build MailAddress from a string.
     *
     * @param string $mailAddressString
     *
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    public function buildMailAddressFromString(string $mailAddressString): MailAddress
    {
        $displayName = null;

        $mailAddressCollectionArray = imap_rfc822_parse_adrlist($mailAddressString, '');

        if (count($mailAddressCollectionArray) !== 1) {
            throw new ValueObjectException('Invalid ' . $this->getClassName() . '.');
        }

        $address = new Address(
            $mailAddressCollectionArray[0]->mailbox .
            (!empty($mailAddressCollectionArray[0]->host) ? '@' . $mailAddressCollectionArray[0]->host : '')
        );

        if (!empty($mailAddressCollectionArray[0]->personal)) {
            $displayName = new DisplayName($mailAddressCollectionArray[0]->personal);
        }

        return new MailAddress($address, $displayName);
    }

    /**
     * @return string
     */
    private function getClassName(): string
    {
        $fullQualifiedNameArray = explode('\\', static::class);

        return end($fullQualifiedNameArray);
    }
}
