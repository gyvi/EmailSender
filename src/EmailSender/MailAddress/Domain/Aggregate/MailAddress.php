<?php

namespace EmailSender\MailAddress\Domain\Aggregate;

use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\DisplayName;
use EmailSender\MailAddress\Application\Catalog\MailAddressPropertyList;
use JsonSerializable;

/**
 * Class MailAddress
 *
 * @package EmailSender\MailAddress
 */
class MailAddress implements JsonSerializable
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
     * MailAddress constructor.
     *
     * @param \EmailSender\Core\ValueObject\Address          $address
     * @param \EmailSender\Core\ValueObject\DisplayName|null $displayName
     */
    public function __construct(Address $address, ?DisplayName $displayName)
    {
        $this->address     = $address;
        $this->displayName = $displayName;
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
    public function jsonSerialize()
    {
        return [
            MailAddressPropertyList::PROPERTY_DISPLAY_NAME => !empty($this->displayName)
                                                                  ? $this->displayName->getValue()
                                                                  : '',
            MailAddressPropertyList::PROPERTY_ADDRESS      => $this->address->getValue(),
        ];
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->displayName ? $this->displayName->getValue() . ' <' . $this->address->getValue() . '>' :
            $this->address->getValue();
    }
}
