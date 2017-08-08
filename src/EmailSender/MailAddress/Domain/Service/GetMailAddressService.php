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
     * @var \EmailSender\MailAddress\Domain\Builder\MailAddressBuilder
     */
    private $mailAddressBuilder;

    /**
     * @var \EmailSender\MailAddress\Domain\Builder\MailAddressCollectionBuilder
     */
    private $mailAddressCollectionBuilder;

    /**
     * GetMailAddressService constructor.
     *
     * @param \EmailSender\MailAddress\Domain\Builder\MailAddressBuilder           $mailAddressBuilder
     * @param \EmailSender\MailAddress\Domain\Builder\MailAddressCollectionBuilder $mailAddressCollectionBuilder
     */
    public function __construct(
        MailAddressBuilder $mailAddressBuilder,
        MailAddressCollectionBuilder $mailAddressCollectionBuilder
    ) {
        $this->mailAddressBuilder           = $mailAddressBuilder;
        $this->mailAddressCollectionBuilder = $mailAddressCollectionBuilder;
    }

    /**
     * Return with MailAddress from the given string.
     *
     * @param string $mailAddressString
     *
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress
     */
    public function getMailAddress(string $mailAddressString): MailAddress
    {
        return $this->mailAddressBuilder->buildMailAddressFromString($mailAddressString);
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
        return $this->mailAddressCollectionBuilder->buildMailAddressCollectionFromString($mailAddressCollectionString);
    }

    /**
     * @param array $mailAddressCollectionArray
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getMailAddressCollectionFromRepository(array $mailAddressCollectionArray): MailAddressCollection
    {
        return $this->mailAddressCollectionBuilder->buildMailAddressCollectionFromArray($mailAddressCollectionArray);
    }
}
