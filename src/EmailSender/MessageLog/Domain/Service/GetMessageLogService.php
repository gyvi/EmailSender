<?php

namespace EmailSender\MessageLog\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MailAddress\Application\Service\MailAddressService;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryReaderInterface;
use EmailSender\MessageLog\Domain\Builder\MessageLogBuilder;
use EmailSender\Recipients\Application\Service\RecipientsService;

/**
 * Class GetMessageLogService
 *
 * @package EmailSender\MessageLog\Domain\Service
 */
class GetMessageLogService
{
    /**
     * @var \EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryReaderInterface
     */
    private $repositoryReader;

    /**
     * @var \EmailSender\Recipients\Application\Service\RecipientsService
     */
    private $recipientsService;

    /**
     * @var \EmailSender\MailAddress\Application\Service\MailAddressService
     */
    private $mailAddressService;

    /**
     * GetMessageLogService constructor.
     *
     * @param \EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryReaderInterface $repositoryReader
     * @param \EmailSender\Recipients\Application\Service\RecipientsService               $recipientsService
     * @param \EmailSender\MailAddress\Application\Service\MailAddressService             $mailAddressService
     */
    public function __construct(
        MessageLogRepositoryReaderInterface $repositoryReader,
        RecipientsService $recipientsService,
        MailAddressService $mailAddressService
    ) {
        $this->repositoryReader   = $repositoryReader;
        $this->recipientsService  = $recipientsService;
        $this->mailAddressService = $mailAddressService;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageLogId
     *
     * @return \EmailSender\MessageLog\Domain\Aggregate\MessageLog
     */
    public function readByMessageLogId(UnsignedInteger $messageLogId): MessageLog
    {
        $messageLogArray   = $this->repositoryReader->readByMessageLogId($messageLogId);
        $messageLogBuilder = new MessageLogBuilder($this->recipientsService, $this->mailAddressService);

        return $messageLogBuilder->buildMessageLogFromArray($messageLogArray);
    }
}
