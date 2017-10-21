<?php

namespace EmailSender\MessageLog\Domain\Factory;

use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Core\Scalar\Application\Factory\DateTimeFactory;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\SignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Core\ValueObject\Subject;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageLog\Infrastructure\Persistence\MessageLogFieldList;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;

/**
 * Class MessageLogFactory
 *
 * @package EmailSender\MessageLog
 */
class MessageLogFactory
{
    /**
     * @var \EmailSender\Core\Factory\RecipientsFactory
     */
    private $recipientsFactory;

    /**
     * @var \EmailSender\Core\Factory\EmailAddressFactory
     */
    private $emailAddressFactory;

    /**
     * @var \EmailSender\Core\Scalar\Application\Factory\DateTimeFactory
     */
    private $dateTimeFactory;

    /**
     * MessageLogFactory constructor.
     *
     * @param \EmailSender\Core\Factory\RecipientsFactory                  $recipientsFactory
     * @param \EmailSender\Core\Factory\EmailAddressFactory                $emailAddressFactory
     * @param \EmailSender\Core\Scalar\Application\Factory\DateTimeFactory $dateTimeFactory
     */
    public function __construct(
        RecipientsFactory $recipientsFactory,
        EmailAddressFactory $emailAddressFactory,
        DateTimeFactory $dateTimeFactory
    ) {
        $this->recipientsFactory   = $recipientsFactory;
        $this->emailAddressFactory = $emailAddressFactory;
        $this->dateTimeFactory     = $dateTimeFactory;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message           $message
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @return \EmailSender\MessageLog\Domain\Aggregate\MessageLog
     */
    public function createFromMessage(Message $message, MessageStore $messageStore): MessageLog
    {
        return new MessageLog(
            $messageStore->getMessageId(),
            $message->getFrom(),
            $messageStore->getRecipients(),
            $message->getSubject(),
            $message->getDelay()
        );
    }

    /**
     * @param array $messageLogArray
     *
     * @return \EmailSender\MessageLog\Domain\Aggregate\MessageLog
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function createFromArray(array $messageLogArray): MessageLog
    {
        $messageLog = new MessageLog(
            new UnsignedInteger($messageLogArray[MessageLogFieldList::MESSAGE_ID]),
            $this->emailAddressFactory->create($messageLogArray[MessageLogFieldList::FROM]),
            $this->recipientsFactory->createFromArray(json_decode($messageLogArray[MessageLogFieldList::RECIPIENTS])),
            new Subject($messageLogArray[MessageLogFieldList::SUBJECT]),
            new UnsignedInteger($messageLogArray[MessageLogFieldList::DELAY])
        );

        $messageLog->setMessageLogId(new UnsignedInteger($messageLogArray[MessageLogFieldList::MESSAGE_LOG_ID]));
        $messageLog->setStatus(new SignedInteger((int)$messageLogArray[MessageLogFieldList::STATUS]));
        $messageLog->setLogged(
            $this->dateTimeFactory->buildFromDateTime(new \DateTime($messageLogArray[MessageLogFieldList::LOGGED]))
        );

        if (!empty($messageLogArray[MessageLogFieldList::QUEUED])) {
            $messageLog->setQueued(
                $this->dateTimeFactory->buildFromDateTime(new \DateTime($messageLogArray[MessageLogFieldList::QUEUED]))
            );
        }

        if (!empty($messageLogArray[MessageLogFieldList::SENT])) {
            $messageLog->setSent(
                $this->dateTimeFactory->buildFromDateTime(new \DateTime($messageLogArray[MessageLogFieldList::SENT]))
            );
        }

        if (!empty($messageLogArray[MessageLogFieldList::ERROR_MESSAGE])) {
            $messageLog->setErrorMessage(new StringLiteral($messageLogArray[MessageLogFieldList::ERROR_MESSAGE]));
        }

        return $messageLog;
    }
}
