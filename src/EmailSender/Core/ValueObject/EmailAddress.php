<?php

namespace EmailSender\Core\ValueObject;

use EmailSender\Core\Catalog\EmailAddressPropertyNameList;
use JsonSerializable;

/**
 * Class EmailAddress
 *
 * @package EmailSender\Core
 */
class EmailAddress implements JsonSerializable
{
    /**
     * @var \EmailSender\Core\ValueObject\Name|null
     */
    private $name;

    /**
     * @var \EmailSender\Core\ValueObject\Address
     */
    private $address;

    /**
     * EmailAddress constructor.
     *
     * @param \EmailSender\Core\ValueObject\Address   $address
     * @param \EmailSender\Core\ValueObject\Name|null $name
     */
    public function __construct(Address $address, ?Name $name)
    {
        $this->address = $address;
        $this->name    = $name;
    }

    /**
     * @return \EmailSender\Core\ValueObject\Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @return \EmailSender\Core\ValueObject\Name|null
     */
    public function getName(): ?Name
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            EmailAddressPropertyNameList::NAME    => $this->name ?? '',
            EmailAddressPropertyNameList::ADDRESS => $this->address,
        ];
    }
}
