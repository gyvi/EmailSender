<?php

namespace EmailSender\MessageStore\Domain\Builder;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\MailAddress\Application\Collection\MailAddressCollection;
use EmailSender\MailAddress\Application\Service\MailAddressService;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Contract\EmailBuilderInterface;

/**
 * Class MessageStoreBuilder
 *
 * @package EmailSender\MessageStore
 */
class MessageStoreBuilder
{
    /**
     * @var \EmailSender\MessageStore\Domain\Contract\EmailBuilderInterface
     */
    private $emailBuilder;

    /**
     * MessageStoreBuilder constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\EmailBuilderInterface $emailBuilder
     */
    public function __construct(EmailBuilderInterface $emailBuilder)
    {
        $this->emailBuilder = $emailBuilder;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function buildMessageStoreFromMessage(Message $message): MessageStore
    {
        $recipients = $this->getAllRecipients($message);

        $renderedMessage = $this->emailBuilder->buildEmailFromMessage($message);

        return new MessageStore($recipients, $renderedMessage);
    }

    /**
     * @param int    $messageId
     * @param string $recipients
     * @param string $message
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     */
    public function buildMessageStoreFromRepository(int $messageId, string $recipients, string $message): MessageStore
    {
        $mailAddressService    = new MailAddressService();
        $mailAddressCollection = $mailAddressService->getMailAddressCollectionFromArray(json_decode($recipients));

        $messageStore = new MessageStore(
            $mailAddressCollection,
            new StringLiteral($message)
        );

        $messageStore->setMessageId(new UnsignedInteger($messageId));

        return $messageStore;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\MailAddress\Application\Collection\MailAddressCollection
     */
    private function getAllRecipients(Message $message): MailAddressCollection
    {
        $allRecipients = clone $message->getTo();

        foreach ($message->getCc() as $mailAddress)
        {
            $allRecipients->add($mailAddress);
        }

        foreach ($message->getBcc() as $mailAddress)
        {
            $allRecipients->add($mailAddress);
        }

        return $allRecipients;
    }
}
