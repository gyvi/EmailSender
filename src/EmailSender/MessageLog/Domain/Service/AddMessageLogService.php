<?php

namespace EmailSender\MessageLog\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MailAddress\Application\Service\MailAddressService;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryWriterInterface;
use EmailSender\MessageLog\Domain\Builder\MessageLogBuilder;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;
use EmailSender\Recipients\Application\Service\RecipientsService;

/**
 * Class AddMessageLogService
 *
 * @package EmailSender\MessageLog\Domain\Service
 */
class AddMessageLogService
{
    /**
     * @var \EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryWriterInterface
     */
    private $repositoryWriter;

    /**
     * @var \EmailSender\Recipients\Application\Service\RecipientsService
     */
    private $recipientsService;

    /**
     * @var \EmailSender\MailAddress\Application\Service\MailAddressService
     */
    private $mailAddressService;

    /**
     * AddMessageLogService constructor.
     *
     * @param \EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryWriterInterface $repositoryWriter
     * @param \EmailSender\Recipients\Application\Service\RecipientsService               $recipientsService
     * @param \EmailSender\MailAddress\Application\Service\MailAddressService             $mailAddressService
     */
    public function __construct(
        MessageLogRepositoryWriterInterface $repositoryWriter,
        RecipientsService $recipientsService,
        MailAddressService $mailAddressService
    ) {
        $this->repositoryWriter   = $repositoryWriter;
        $this->recipientsService  = $recipientsService;
        $this->mailAddressService = $mailAddressService;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message           $message
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @return \EmailSender\MessageLog\Domain\Aggregate\MessageLog
     */
    public function add(Message $message, MessageStore $messageStore): MessageLog
    {
        $messageLogBuilder = new MessageLogBuilder($this->recipientsService, $this->mailAddressService);
        $messageLog        = $messageLogBuilder->buildMessageLogFromMessage($message, $messageStore);
        $messageLogId      = new UnsignedInteger($this->repositoryWriter->add($messageLog));

        $messageLog->setMessageLogId($messageLogId);

        return $messageLog;
    }
}
