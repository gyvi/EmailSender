<?php

namespace EmailSender\MailAddress\Application\Collection;

use EmailSender\Core\Collection\Collection;
use EmailSender\MailAddress\Domain\Aggregate\MailAddress;

/**
 * Class MailAddressCollection
 *
 * @package EmailSender\MailAddress
 */
class MailAddressCollection extends Collection
{
    /**
     * @param \EmailSender\MailAddress\Domain\Aggregate\MailAddress $item
     */
    public function add($item): void
    {
        parent::add($item);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return MailAddress::class;
    }
}
