<?php

namespace EmailSender\MailAddress\Application\Service;

use EmailSender\MailAddress\Application\Collection\MailAddressCollection;
use EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface;
use EmailSender\MailAddress\Domain\Builder\MailAddressBuilder;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\MailAddress\Domain\Builder\MailAddressCollectionBuilder;
use EmailSender\MailAddress\Domain\Service\GetMailAddressService;

/**
 * Class MailAddressService
 *
 * @package EmailSender\MailAddress\Application\Service
 */
class MailAddressService implements MailAddressServiceInterface
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
        $getMailAddressService = new GetMailAddressService();

        return $getMailAddressService->getMailAddress($mailAddressString);
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
        $getMailAddressService = new GetMailAddressService();

        return $getMailAddressService->getMailAddressCollectionFromRequest($mailAddressCollectionString);
    }

    /**
     * @param array $mailAddressCollectionArray
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getMailAddressCollectionFromRepository(array $mailAddressCollectionArray): MailAddressCollection
    {
        $getMailAddressService = new GetMailAddressService();

        return $getMailAddressService->getMailAddressCollectionFromRepository($mailAddressCollectionArray);
    }
}
