<?php

namespace EmailSender\Core\Factory;

use EmailSender\Core\Catalog\RecipientsPropertyNameList;
use EmailSender\Core\Collection\EmailAddressCollection;
use EmailSender\Core\Entity\Recipients;
use EmailSender\Message\Domain\Aggregate\Message;

/**
 * Class RecipientsFactory
 *
 * @package EmailSender\Core
 */
class RecipientsFactory
{
    /**
     * @var \EmailSender\Core\Factory\EmailAddressCollectionFactory
     */
    private $emailAddressCollectionFactory;

    /**
     * RecipientsFactory constructor.
     *
     * @param \EmailSender\Core\Factory\EmailAddressCollectionFactory $emailAddressCollectionFactory
     */
    public function __construct(EmailAddressCollectionFactory $emailAddressCollectionFactory)
    {
        $this->emailAddressCollectionFactory = $emailAddressCollectionFactory;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\Core\Entity\Recipients
     * @throws \InvalidArgumentException
     */
    public function createFromMessage(Message $message): Recipients
    {
        // Use this array for collect unique email addresses.
        $allRecipientsEmailAddresses = [];

        $to  = new EmailAddressCollection();
        $cc  = new EmailAddressCollection();
        $bcc = new EmailAddressCollection();

        /** @var \EmailSender\Core\ValueObject\EmailAddress $toAddress */
        foreach ($message->getTo() as $toAddress) {
            if (!in_array(strtolower($toAddress->getAddress()->getValue()), $allRecipientsEmailAddresses, true)) {
                $to->add($toAddress);
                $allRecipientsEmailAddresses[] = strtolower($toAddress->getAddress()->getValue());
            }
        }

        /** @var \EmailSender\Core\ValueObject\EmailAddress $ccAddress */
        foreach ($message->getCc() as $ccAddress) {
            if (!in_array(strtolower($ccAddress->getAddress()->getValue()), $allRecipientsEmailAddresses, true)) {
                $cc->add($ccAddress);
                $allRecipientsEmailAddresses[] = strtolower($ccAddress->getAddress()->getValue());
            }
        }

        /** @var \EmailSender\Core\ValueObject\EmailAddress $bccAddress */
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
     * @return \EmailSender\Core\Entity\Recipients
     * @throws \InvalidArgumentException
     */
    public function createFromArray(array $recipientsArray): Recipients
    {
        $to = $this->emailAddressCollectionFactory->createFromArray($recipientsArray[RecipientsPropertyNameList::TO]);

        $cc = new EmailAddressCollection();

        if (!empty($recipientsArray[RecipientsPropertyNameList::CC])) {
            $cc = $this->emailAddressCollectionFactory
                ->createFromArray($recipientsArray[RecipientsPropertyNameList::CC]);
        }

        $bcc = new EmailAddressCollection();

        if (!empty($recipientsArray[RecipientsPropertyNameList::BCC])) {
            $bcc = $this->emailAddressCollectionFactory
                ->createFromArray($recipientsArray[RecipientsPropertyNameList::BCC]);
        }

        return new Recipients($to, $cc, $bcc);
    }
}
