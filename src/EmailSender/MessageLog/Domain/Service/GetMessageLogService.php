<?php

namespace EmailSender\MessageLog\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageLog\Application\Collection\MessageLogCollection;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageLog\Domain\Factory\ListMessageLogsRequestFactory;
use EmailSender\MessageLog\Domain\Factory\MessageLogCollectionFactory;
use EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryReaderInterface;
use EmailSender\MessageLog\Domain\Factory\MessageLogFactory;

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
     * @var \EmailSender\MessageLog\Domain\Factory\MessageLogFactory
     */
    private $messageLogBuilder;

    /**
     * @var \EmailSender\MessageLog\Domain\Factory\MessageLogCollectionFactory
     */
    private $messageLogCollectionBuilder;

    /**
     * @var \EmailSender\MessageLog\Domain\Factory\ListMessageLogsRequestFactory
     */
    private $listMessageLogsRequestBuilder;

    /**
     * GetMessageLogService constructor.
     *
     * @param \EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryReaderInterface $repositoryReader
     * @param \EmailSender\MessageLog\Domain\Factory\MessageLogFactory                    $messageLogBuilder
     * @param \EmailSender\MessageLog\Domain\Factory\MessageLogCollectionFactory          $messageLogCollectionBuilder
     * @param \EmailSender\MessageLog\Domain\Factory\ListMessageLogsRequestFactory        $listMessageLogsRequestBuilder
     */
    public function __construct(
        MessageLogRepositoryReaderInterface $repositoryReader,
        MessageLogFactory $messageLogBuilder,
        MessageLogCollectionFactory $messageLogCollectionBuilder,
        ListMessageLogsRequestFactory $listMessageLogsRequestBuilder
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

        return $this->messageLogBuilder->createFromArray($messageLogArray);
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
        $listMessageLogsRequest = $this->listMessageLogsRequestBuilder->create($request);

        $messageLogCollectionArray = $this->repositoryReader->listMessageLogs($listMessageLogsRequest);

        return $this->messageLogCollectionBuilder->create($messageLogCollectionArray);
    }
}
