<?php

namespace EmailSender\MessageLog\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageLog\Application\Collection\MessageLogCollection;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageLog\Domain\Builder\ListMessageLogsRequestBuilder;
use EmailSender\MessageLog\Domain\Builder\MessageLogCollectionBuilder;
use EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryReaderInterface;
use EmailSender\MessageLog\Domain\Builder\MessageLogBuilder;

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
     * @var \EmailSender\MessageLog\Domain\Builder\MessageLogBuilder
     */
    private $messageLogBuilder;

    /**
     * @var \EmailSender\MessageLog\Domain\Builder\MessageLogCollectionBuilder
     */
    private $messageLogCollectionBuilder;

    /**
     * @var \EmailSender\MessageLog\Domain\Builder\ListMessageLogsRequestBuilder
     */
    private $listMessageLogsRequestBuilder;

    /**
     * GetMessageLogService constructor.
     *
     * @param \EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryReaderInterface $repositoryReader
     * @param \EmailSender\MessageLog\Domain\Builder\MessageLogBuilder                    $messageLogBuilder
     * @param \EmailSender\MessageLog\Domain\Builder\MessageLogCollectionBuilder          $messageLogCollectionBuilder
     * @param \EmailSender\MessageLog\Domain\Builder\ListMessageLogsRequestBuilder        $listMessageLogsRequestBuilder
     */
    public function __construct(
        MessageLogRepositoryReaderInterface $repositoryReader,
        MessageLogBuilder $messageLogBuilder,
        MessageLogCollectionBuilder $messageLogCollectionBuilder,
        ListMessageLogsRequestBuilder $listMessageLogsRequestBuilder
    ) {
        $this->repositoryReader              = $repositoryReader;
        $this->messageLogBuilder             = $messageLogBuilder;
        $this->messageLogCollectionBuilder   = $messageLogCollectionBuilder;
        $this->listMessageLogsRequestBuilder = $listMessageLogsRequestBuilder;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageLogId
     *
     * @return \EmailSender\MessageLog\Domain\Aggregate\MessageLog
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function readByMessageLogId(UnsignedInteger $messageLogId): MessageLog
    {
        $messageLogArray = $this->repositoryReader->readByMessageLogId($messageLogId);

        return $this->messageLogBuilder->buildMessageLogFromArray($messageLogArray);
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\MessageLog\Application\Collection\MessageLogCollection
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function listMessageLogs(array $request): MessageLogCollection
    {
        $listMessageLogsRequest = $this->listMessageLogsRequestBuilder->buildListMessageLogsRequestFromArray($request);

        $messageLogCollectionArray = $this->repositoryReader->listMessageLogs($listMessageLogsRequest);

        return $this->messageLogCollectionBuilder->buildMessageLogCollectionFromArray($messageLogCollectionArray);
    }
}
