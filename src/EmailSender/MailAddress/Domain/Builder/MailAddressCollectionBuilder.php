<?php

namespace EmailSender\MailAddress\Domain\Builder;

use EmailSender\MailAddress\Application\Collection\MailAddressCollection;

/**
 * Class MailAddressCollectionBuilder
 *
 * @package EmailSender\MailAddress
 */
class MailAddressCollectionBuilder
{
    /**
     * @var \EmailSender\MailAddress\Domain\Builder\MailAddressBuilder
     */
    private $mailAddressBuilder;

    /**
     * MailAddressCollectionBuilder constructor.
     *
     * @param \EmailSender\MailAddress\Domain\Builder\MailAddressBuilder $mailAddressBuilder
     */
    public function __construct(MailAddressBuilder $mailAddressBuilder)
    {
        $this->mailAddressBuilder = $mailAddressBuilder;
    }

    /**
     * Build MailAddressCollection from string.
     *
     * @param string $mailAddressCollectionString
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    public function buildMailAddressCollectionFromString(string $mailAddressCollectionString): MailAddressCollection
    {
        $mailAddressCollection = new MailAddressCollection();

        $explodedMailAddressCollectionString = explode(';', $mailAddressCollectionString);

        if (!empty($explodedMailAddressCollectionString)
            && is_array($explodedMailAddressCollectionString)
        ) {
            /** @var string $mailAddressString */
            foreach ($explodedMailAddressCollectionString as $mailAddressString) {
                $mailAddressCollection->add($this->mailAddressBuilder->buildMailAddressFromString($mailAddressString));
            }
        }

        return $mailAddressCollection;
    }
}
