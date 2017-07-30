<?php

namespace EmailSender\MailAddress\Domain\Builder;

use EmailSender\MailAddress\Domain\Aggregate\MailAddress;
use EmailSender\Core\ValueObject\Address;
use EmailSender\Core\ValueObject\DisplayName;

/**
 * Class MailAddressBuilder
 *
 * @package EmailSender\MailAddress
 */
class MailAddressBuilder
{
    /**
     * Build MailAddress from a string.
     *
     * @param string $mailAddressString
     *
     * @return \EmailSender\MailAddress\Domain\Aggregate\MailAddress
     */
    public function buildMailAddressFromString(string $mailAddressString): MailAddress
    {
        $displayName = null;

        $sanitizedMailAddressString = $this->sanitizeMailAddressString($mailAddressString);

        $addressString = $this->getAddressFromString($sanitizedMailAddressString);

        if ($addressString !== trim($sanitizedMailAddressString)
            && $displayNameString = $this->getDisplayNameFromString($sanitizedMailAddressString)
        ) {
            $displayName = new DisplayName($displayNameString);
        }

        $address = new Address($addressString);

        return new MailAddress($address, $displayName);
    }

    /**
     * Sanitize string, remove line breaks and apostrophes.
     *
     * @param string $value
     *
     * @return string
     */
    private function sanitizeMailAddressString(string $value): string
    {
        return preg_replace('/[\r\n"]+/', '', $value);
    }

    /**
     * Get the DisplayName from the string.
     *
     * @param string $mailAddressString
     *
     * @return null|string
     */
    private function getDisplayNameFromString(string $mailAddressString): ?string
    {
        $displayNameEndPosition = strpos($mailAddressString, '<');

        if ($displayNameEndPosition !== false && $displayNameEndPosition > 0) {
            $displayNameString = substr($mailAddressString, 0, $displayNameEndPosition);

            if (strlen(trim($displayNameString)) > 0) {
                return trim($displayNameString);
            }
        }

        return null;
    }

    /**
     * Get the address from the string.
     *
     * @param string $mailAddressString
     *
     * @return string
     */
    private function getAddressFromString(string $mailAddressString): string
    {
        if (preg_match('/(?<=<).*(?=>)/', $mailAddressString, $matches)) {
            return trim($matches[0]);
        }

        return trim($mailAddressString);
    }
}
