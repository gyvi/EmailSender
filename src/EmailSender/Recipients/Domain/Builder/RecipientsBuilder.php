<?php

namespace EmailSender\Recipients\Domain\Builder;

use EmailSender\MailAddress\Application\Collection\MailAddressCollection;
use EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Recipients\Application\Catalog\RecipientsPropertyNames;
use EmailSender\Recipients\Domain\Aggregate\Recipients;
use InvalidArgumentException;

/**
 * Class RecipientsBuilder
 *
 * @package EmailSender\Recipients
 */
class RecipientsBuilder
{
    /**
     * @var \EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface
     */
    private $mailAddressService;

    /**
     * RecipientsBuilder constructor.
     *
     * @param \EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface $mailAddressService
     */
    public function __construct(MailAddressServiceInterface $mailAddressService)
    {
        $this->mailAddressService = $mailAddressService;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function buildRecipientsFromMessage(Message $message): Recipients
    {
        // Use this array for collect unique email addresses.
        $allRecipientsEmailAddresses = [];

        $to  = new MailAddressCollection();
        $cc  = new MailAddressCollection();
        $bcc = new MailAddressCollection();

        /** @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress $toAddress */
        foreach ($message->getTo() as $toAddress) {
            if (!in_array(strtolower($toAddress->getAddress()->getValue()), $allRecipientsEmailAddresses, true)) {
                $to->add($toAddress);
                $allRecipientsEmailAddresses[] = strtolower($toAddress->getAddress()->getValue());
            }
        }

        /** @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress $ccAddress */
        foreach ($message->getCc() as $ccAddress) {
            if (!in_array(strtolower($ccAddress->getAddress()->getValue()), $allRecipientsEmailAddresses, true)) {
                $cc->add($ccAddress);
                $allRecipientsEmailAddresses[] = strtolower($ccAddress->getAddress()->getValue());
            }
        }

        /** @var \EmailSender\MailAddress\Domain\Aggregate\MailAddress $bccAddress */
        foreach ($message->getBcc() as $bccAddress) {
            if (!in_array(strtolower($bccAddress->getAddress()->getValue()), $allRecipientsEmailAddresses, true)) {
                $bcc->add($bccAddress);
                $allRecipientsEmailAddresses[] = strtolower($ccAddress->getAddress()->getValue());
            }
        }

        return new Recipients($to, $cc, $bcc);
    }

    /**
     * @param array $recipientsArray
     *
     * @return \EmailSender\Recipients\Domain\Aggregate\Recipients
     */
    public function buildRecipientsFromArray(array $recipientsArray): Recipients
    {
        if (!empty($recipientsArray[RecipientsPropertyNames::TO])) {
            $to = $this->mailAddressService
                ->getMailAddressCollectionFromRepository($recipientsArray[RecipientsPropertyNames::TO]);
        } else {
            throw new InvalidArgumentException('Empty recipients field!');
        }

        if (!empty($recipientsArray[RecipientsPropertyNames::CC])) {
            $cc = $this->mailAddressService
                ->getMailAddressCollectionFromRepository($recipientsArray[RecipientsPropertyNames::CC]);
        } else {
            $cc = new MailAddressCollection();
        }

        if (!empty($recipientsArray[RecipientsPropertyNames::BCC])) {
            $bcc = $this->mailAddressService
                ->getMailAddressCollectionFromRepository($recipientsArray[RecipientsPropertyNames::BCC]);
        } else {
            $bcc = new MailAddressCollection();
        }

        return new Recipients($to, $cc, $bcc);
    }
}
