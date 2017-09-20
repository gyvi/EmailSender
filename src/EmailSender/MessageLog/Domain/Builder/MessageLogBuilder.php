<?php

namespace EmailSender\MessageLog\Domain\Builder;

use EmailSender\Core\Scalar\Application\Factory\DateTimeFactory;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\SignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Core\ValueObject\Subject;
use EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageLog\Infrastructure\Persistence\MessageLogFieldList;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\Recipients\Application\Contract\RecipientsServiceInterface;

/**
 * Class MessageLogBuilder
 *
 * @package EmailSender\MessageLog
 */
class MessageLogBuilder
{
    /**
     * @var \EmailSender\Recipients\Application\Contract\RecipientsServiceInterface
     */
    private $recipientsService;

    /**
     * @var \EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface
     */
    private $mailAddressService;

    /**
     * @var \EmailSender\Core\Scalar\Application\Factory\DateTimeFactory
     */
    private $dateTimeFactory;

    /**
     * MessageLogBuilder constructor.
     *
     * @param \EmailSender\Recipients\Application\Contract\RecipientsServiceInterface   $recipientsService
     * @param \EmailSender\MailAddress\Application\Contract\MailAddressServiceInterface $mailAddressService
     * @param \EmailSender\Core\Scalar\Application\Factory\DateTimeFactory              $dateTimeFactory
     */
    public function __construct(
        RecipientsServiceInterface $recipientsService,
        MailAddressServiceInterface $mailAddressService,
        DateTimeFactory $dateTimeFactory
    ) {
        $this->recipientsService  = $recipientsService;
        $this->mailAddressService = $mailAddressService;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message           $message
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @return \EmailSender\MessageLog\Domain\Aggregate\MessageLog
     */
    public function buildMessageLogFromMessage(Message $message, MessageStore $messageStore): MessageLog
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
     */
    public function buildMessageLogFromArray(array $messageLogArray): MessageLog
    {
        $messageLog = new MessageLog(
            new UnsignedInteger($messageLogArray[MessageLogFieldList::MESSAGE_ID]),
            $this->mailAddressService->getMailAddress($messageLogArray[MessageLogFieldList::FROM]),
            $this->recipientsService->getRecipientsFromJson($messageLogArray[MessageLogFieldList::RECIPIENTS]),
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
