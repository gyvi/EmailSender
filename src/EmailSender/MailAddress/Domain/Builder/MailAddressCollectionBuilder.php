<?php

namespace EmailSender\MailAddress\Domain\Builder;

use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\DisplayName;
use EmailSender\MailAddress\Application\Collection\MailAddressCollection;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;

/**
 * Class MailAddressCollectionBuilder
 *
 * @package EmailSender\MailAddress
 */
class MailAddressCollectionBuilder
{
    /**
     * @var \EmailSender\MailAddress\Domain\Builder\MailAddressBuilder
     */
    private $mailAddressBuilder;

    /**
     * MailAddressCollectionBuilder constructor.
     *
     * @param \EmailSender\MailAddress\Domain\Builder\MailAddressBuilder $mailAddressBuilder
     */
    public function __construct(MailAddressBuilder $mailAddressBuilder)
    {
        $this->mailAddressBuilder = $mailAddressBuilder;
    }

    /**
     * Build MailAddressCollection from string.
     *
     * @param string $mailAddressCollectionString
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function buildMailAddressCollectionFromString(string $mailAddressCollectionString): MailAddressCollection
    {
        $mailAddressCollection = new MailAddressCollection();

        if (!empty(trim($mailAddressCollectionString))) {

            $mailAddressCollectionArray = imap_rfc822_parse_adrlist($mailAddressCollectionString, '');

            /** @var \stdClass $mailAddressObject */
            foreach ($mailAddressCollectionArray as $mailAddressObject) {

                $mailAddressString = imap_rfc822_write_address(
                    isset($mailAddressObject->mailbox)  ? $mailAddressObject->mailbox  : '',
                    isset($mailAddressObject->host)     ? $mailAddressObject->host     : '',
                    isset($mailAddressObject->personal) ? $mailAddressObject->personal : ''
                );

                if (!empty(trim($mailAddressString)))
                {
                    $mailAddressCollection->add(
                        $this->mailAddressBuilder->buildMailAddressFromString($mailAddressString)
                    );
                }
            }
        }

        return $mailAddressCollection;
    }

    /**
     * @param array $mailAddressCollectionArray
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function buildMailAddressCollectionFromArray(array $mailAddressCollectionArray): MailAddressCollection
    {
        $mailAddressCollection = new MailAddressCollection();

        foreach ($mailAddressCollectionArray as $mailAddressArray) {
            $mailAddressCollection->add(
                new MailAddress(
                    new Address($mailAddressArray[MailAddress::FIELD_ADDRESS]),
                    new DisplayName($mailAddressArray[MailAddress::FIELD_DISPLAYNAME])
                )
            );
        }

        return $mailAddressCollection;
    }
}
