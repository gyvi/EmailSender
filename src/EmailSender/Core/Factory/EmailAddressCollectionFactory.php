<?php

namespace EmailSender\Core\Factory;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use EmailSender\Core\Collection\EmailAddressCollection;
use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\DisplayName;
use EmailSender\Core\ValueObject\EmailAddress;

/**
 * Class EmailAddressCollectionFactory
 *
 * @package EmailSender\Core
 */
class EmailAddressCollectionFactory
{
    /**
     * @var \EmailSender\Core\Factory\EmailAddressFactory
     */
    private $emailAddressFactory;

    /**
     * EmailAddressCollectionFactory constructor.
     *
     * @param \EmailSender\Core\Factory\EmailAddressFactory $emailAddressFactory
     */
    public function __construct(EmailAddressFactory $emailAddressFactory)
    {
        $this->emailAddressFactory = $emailAddressFactory;
    }

    /**
     * @param string $emailAddressCollectionString
     *
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function createFromString(string $emailAddressCollectionString): EmailAddressCollection
    {
        $emailAddressCollection = new EmailAddressCollection();

        if (!empty(trim($emailAddressCollectionString))) {
            $mailAddressCollectionArray = imap_rfc822_parse_adrlist($emailAddressCollectionString, '');

            /** @var \stdClass $mailAddressObject */
            foreach ($mailAddressCollectionArray as $mailAddressObject) {
                $mailAddressString = imap_rfc822_write_address(
                    $mailAddressObject->mailbox ?? '',
                    $mailAddressObject->host ?? '',
                    $mailAddressObject->personal ?? ''
                );

                if (!empty(trim($mailAddressString))) {
                    $emailAddressCollection->add(
                        $this->emailAddressFactory->create($mailAddressString)
                    );
                }
            }
        }

        return $emailAddressCollection;
    }

    /**
     * @param array $emailAddressCollectionArray
     *
     * @return \EmailSender\Core\Collection\EmailAddressCollection
     * @throws \InvalidArgumentException
     */
    public function createFromArray(array $emailAddressCollectionArray): EmailAddressCollection
    {
        $emailAddressCollection = new EmailAddressCollection();

        foreach ($emailAddressCollectionArray as $emailAddressArray) {
            $emailAddressCollection->add(
                new EmailAddress(
                    new Address($emailAddressArray[EmailAddressPropertyNameList::ADDRESS]),
                    (!empty($emailAddressArray[EmailAddressPropertyNameList::DISPLAY_NAME])
                        ? new DisplayName($emailAddressArray[EmailAddressPropertyNameList::DISPLAY_NAME])
                        : null
                    )
                )
            );
        }

        return $emailAddressCollection;
    }
}
