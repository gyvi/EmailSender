<?php

namespace EmailSender\Core\Collection;

use EmailSender\Core\ValueObject\EmailAddress;

/**
 * Class EmailAddressCollection
 *
 * @package EmailSender\Core
 */
class EmailAddressCollection extends Collection
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return EmailAddress::class;
    }
}
