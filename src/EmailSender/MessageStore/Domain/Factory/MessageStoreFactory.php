<?php

namespace EmailSender\MessageStore\Domain\Factory;

use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\MessageStore\Domain\Contract\EmailComposerInterface;
use EmailSender\MessageStore\Infrastructure\Persistence\MessageStoreFieldList;

/**
 * Class MessageStoreFactory
 *
 * @package EmailSender\MessageStore
 */
class MessageStoreFactory
{
    /**
     * @var \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface
     */
    private $emailComposer;

    /**
     * @var \EmailSender\Core\Factory\RecipientsFactory
     */
    private $recipientsFactory;

    /**
     * MessageStoreFactory constructor.
     *
     * @param \EmailSender\MessageStore\Domain\Contract\EmailComposerInterface $emailComposer
     * @param \EmailSender\Core\Factory\RecipientsFactory                      $recipientsFactory
     */
    public function __construct(EmailComposerInterface $emailComposer, RecipientsFactory $recipientsFactory)
    {
        $this->emailComposer     = $emailComposer;
        $this->recipientsFactory = $recipientsFactory;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message $message
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     * @throws \InvalidArgumentException
     * @throws \phpmailerException
     */
    public function createFromMessage(Message $message): MessageStore
    {
        $recipients      = $this->recipientsFactory->createFromMessage($message);
        $renderedMessage = $this->emailComposer->compose($message);

        return new MessageStore($recipients, $renderedMessage);
    }

    /**
     * @param array $messageStoreArray
     *
     * @return \EmailSender\MessageStore\Domain\Aggregate\MessageStore
     * @throws \InvalidArgumentException
     */
    public function createFromArray(array $messageStoreArray): MessageStore
    {
        $recipients = $this->recipientsFactory->createFromArray(
            json_decode($messageStoreArray[MessageStoreFieldList::FIELD_RECIPIENTS])
        );

        $messageStore = new MessageStore(
            $recipients,
            new StringLiteral($messageStoreArray[MessageStoreFieldList::FIELD_MESSAGE])
        );

        $messageStore->setMessageId(new UnsignedInteger($messageStoreArray[MessageStoreFieldList::FIELD_MESSAGE_ID]));

        return $messageStore;
    }
}
