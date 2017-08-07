<?php

namespace EmailSender\MailAddress\Domain\Service;

use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\MailAddress\Domain\Builder\MailAddressBuilder;
use EmailSender\MailAddress\Domain\Builder\MailAddressCollectionBuilder;
use EmailSender\MailAddress\Application\Collection\MailAddressCollection;

/**
 * Class GetMailAddressService
 *
 * @package EmailSender\MailAddress
 */
class GetMailAddressService
{
    /**
     * Return with MailAddress from the given string.
     *
     * @param string $mailAddressString
     *
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress
     */
    public function getMailAddress(string $mailAddressString): MailAddress
    {
        $mailAddressBuilder = new MailAddressBuilder();

        return $mailAddressBuilder->buildMailAddressFromString($mailAddressString);
    }

    /**
     * Return with MailAddressCollection from the given string.
     *
     * @param string $mailAddressCollectionString
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getMailAddressCollectionFromRequest(string $mailAddressCollectionString): MailAddressCollection
    {
        $mailAddressBuilder           = new MailAddressBuilder();
        $mailAddressCollectionBuilder = new MailAddressCollectionBuilder($mailAddressBuilder);

        return $mailAddressCollectionBuilder->buildMailAddressCollectionFromString($mailAddressCollectionString);
    }

    /**
     * @param array $mailAddressCollectionArray
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getMailAddressCollectionFromRepository(array $mailAddressCollectionArray): MailAddressCollection
    {
        $mailAddressBuilder           = new MailAddressBuilder();
        $mailAddressCollectionBuilder = new MailAddressCollectionBuilder($mailAddressBuilder);

        return $mailAddressCollectionBuilder->buildMailAddressCollectionFromArray($mailAddressCollectionArray);
    }
}