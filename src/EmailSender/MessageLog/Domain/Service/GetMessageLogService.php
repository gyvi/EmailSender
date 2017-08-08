<?php

namespace EmailSender\MessageLog\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
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
     * GetMessageLogService constructor.
     *
     * @param \EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryReaderInterface $repositoryReader
     * @param \EmailSender\MessageLog\Domain\Builder\MessageLogBuilder                    $messageLogBuilder
     */
    public function __construct(
        MessageLogRepositoryReaderInterface $repositoryReader,
        MessageLogBuilder $messageLogBuilder
    ) {
        $this->repositoryReader  = $repositoryReader;
        $this->messageLogBuilder = $messageLogBuilder;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $messageLogId
     *
     * @return \EmailSender\MessageLog\Domain\Aggregate\MessageLog
     */
    public function readByMessageLogId(UnsignedInteger $messageLogId): MessageLog
    {
        $messageLogArray = $this->repositoryReader->readByMessageLogId($messageLogId);

        return $this->messageLogBuilder->buildMessageLogFromArray($messageLogArray);
    }
}
