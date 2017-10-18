<?php

namespace EmailSender\MailAddress\Domain\Builder;

use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\DisplayName;
use EmailSender\MailAddress\Application\Catalog\MailAddressPropertyNames;
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
     * @param string $mailAddressCollectionString
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function buildMailAddressCollectionFromString(string $mailAddressCollectionString): MailAddressCollection
    {
        $mailAddressCollection = new MailAddressCollection();

        if (!empty(trim($mailAddressCollectionString))) {
            $mailAddressCollectionArray = imap_rfc822_parse_adrlist($mailAddressCollectionString, '');

            /** @var \stdClass $mailAddressObject */
            foreach ($mailAddressCollectionArray as $mailAddressObject) {
                $mailAddressString = imap_rfc822_write_address(
                    $mailAddressObject->mailbox ?? '',
                    $mailAddressObject->host ?? '',
                    $mailAddressObject->personal ?? ''
                );

                if (!empty(trim($mailAddressString))) {
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
     * @throws \InvalidArgumentException
     */
    public function buildMailAddressCollectionFromArray(array $mailAddressCollectionArray): MailAddressCollection
    {
        $mailAddressCollection = new MailAddressCollection();

        foreach ($mailAddressCollectionArray as $mailAddressArray) {
            $mailAddressCollection->add(
                new MailAddress(
                    new Address($mailAddressArray[MailAddressPropertyNames::ADDRESS]),
                    (!empty($mailAddressArray[MailAddressPropertyNames::DISPLAY_NAME])
                        ? new DisplayName($mailAddressArray[MailAddressPropertyNames::DISPLAY_NAME])
                        : null
                    )
                )
            );
        }

        return $mailAddressCollection;
    }
}
