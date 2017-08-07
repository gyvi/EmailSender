<?php

namespace EmailSender\MailAddress\Application\Contract;

use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\MailAddress\Application\Collection\MailAddressCollection;

/**
 * Interface MailAddressServiceInterface
 *
 * @package EmailSender\MailAddress
 */
interface MailAddressServiceInterface
{
    /**
     * @param string $mailAddressString
     *
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress
     */
    public function getMailAddress(string $mailAddressString): MailAddress;

    /**
     * @param string $mailAddressCollectionString
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getMailAddressCollectionFromRequest(string $mailAddressCollectionString): MailAddressCollection;

    /**
     * @param array $mailAddressCollectionArray
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function getMailAddressCollectionFromRepository(array $mailAddressCollectionArray): MailAddressCollection;
}
