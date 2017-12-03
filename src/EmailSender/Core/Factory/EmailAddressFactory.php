<?php

namespace EmailSender\Core\Factory;

use EmailSender\Core\ValueObject\EmailAddress;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\Name;

/**
 * Class EmailAddressFactory
 *
 * @package EmailSender\Core
 */
class EmailAddressFactory
{
    /**
     * @param string $emailAddress
     *
     * @return \EmailSender\Core\ValueObject\EmailAddress
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    public function create(string $emailAddress): EmailAddress
    {
        $emailAddressCollectionArray = imap_rfc822_parse_adrlist($emailAddress, '');

        if (count($emailAddressCollectionArray) !== 1) {
            throw new ValueObjectException('Invalid email address.');
        }

        $address = new Address(
            $emailAddressCollectionArray[0]->mailbox .
            (!empty($emailAddressCollectionArray[0]->host) ? '@' . $emailAddressCollectionArray[0]->host : '')
        );

        $name = null;
        if (!empty($emailAddressCollectionArray[0]->personal)) {
            $name = new Name($emailAddressCollectionArray[0]->personal);
        }

        return new EmailAddress($address, $name);
    }
}
