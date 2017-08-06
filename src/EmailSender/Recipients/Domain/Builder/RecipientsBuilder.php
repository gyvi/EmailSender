<?php

namespace EmailSender\Recipients\Domain\Builder;

use EmailSender\MailAddress\Application\Collection\MailAddressCollection;
use EmailSender\MailAddress\Application\Service\MailAddressService;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\Recipients\Application\Catalog\RecipientsPropertyList;
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
     * @var \EmailSender\MailAddress\Application\Service\MailAddressService
     */
    private $mailAddressService;

    /**
     * RecipientsBuilder constructor.
     *
     * @param \EmailSender\MailAddress\Application\Service\MailAddressService $mailAddressService
     */
    public function __construct(MailAddressService $mailAddressService)
    {
        $this->mailAddressService    = new MailAddressService();
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
        if (!empty($recipientsArray[RecipientsPropertyList::TO])) {
            $to = $this->mailAddressService
                ->getMailAddressCollectionFromArray($recipientsArray[RecipientsPropertyList::TO]);
        } else {
            throw new InvalidArgumentException('Empty recipients field!');
        }

        if (!empty($recipientsArray[RecipientsPropertyList::CC])) {
            $cc = $this->mailAddressService
                ->getMailAddressCollectionFromArray($recipientsArray[RecipientsPropertyList::CC]);
        } else {
            $cc = new MailAddressCollection();
        }

        if (!empty($recipientsArray[RecipientsPropertyList::BCC])) {
            $bcc = $this->mailAddressService
                ->getMailAddressCollectionFromArray($recipientsArray[RecipientsPropertyList::BCC]);
        } else {
            $bcc = new MailAddressCollection();
        }

        return new Recipients($to, $cc, $bcc);
    }
}
