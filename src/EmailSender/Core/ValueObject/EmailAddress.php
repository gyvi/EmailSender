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
     * @var \EmailSender\Core\ValueObject\Address
     */
    private $address;

    /**
     * @var \EmailSender\Core\ValueObject\DisplayName|null
     */
    private $displayName;

    /**
     * EmailAddress constructor.
     *
     * @param \EmailSender\Core\ValueObject\Address          $address
     * @param \EmailSender\Core\ValueObject\DisplayName|null $displayName
     */
    public function __construct(Address $address, ?DisplayName $displayName)
    {
        $this->address = $address;
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->displayName ? $this->displayName->getValue() . ' <' . $this->address->getValue() . '>' :
            $this->address->getValue();
    }

    /**
     * @return \EmailSender\Core\ValueObject\Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @return \EmailSender\Core\ValueObject\DisplayName|null
     */
    public function getDisplayName(): ?DisplayName
    {
        return $this->displayName;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            EmailAddressPropertyNameList::DISPLAY_NAME => $this->displayName ?? '',
            EmailAddressPropertyNameList::ADDRESS      => $this->address,
        ];
    }
}
