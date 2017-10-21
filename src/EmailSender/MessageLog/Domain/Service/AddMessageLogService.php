<?php

namespace EmailSender\MessageLog\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\MessageLog\Domain\Aggregate\MessageLog;
use EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryWriterInterface;
use EmailSender\MessageLog\Domain\Factory\MessageLogFactory;
use EmailSender\Message\Domain\Aggregate\Message;
use EmailSender\MessageStore\Domain\Aggregate\MessageStore;

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
     * @var \EmailSender\MessageLog\Domain\Factory\MessageLogFactory
     */
    private $messageLogBuilder;

    /**
     * AddMessageLogService constructor.
     *
     * @param \EmailSender\MessageLog\Domain\Contract\MessageLogRepositoryWriterInterface $repositoryWriter
     * @param \EmailSender\MessageLog\Domain\Factory\MessageLogFactory                    $messageLogBuilder
     */
    public function __construct(
        MessageLogRepositoryWriterInterface $repositoryWriter,
        MessageLogFactory $messageLogBuilder
    ) {
        $this->repositoryWriter  = $repositoryWriter;
        $this->messageLogBuilder = $messageLogBuilder;
    }

    /**
     * @param \EmailSender\Message\Domain\Aggregate\Message           $message
     * @param \EmailSender\MessageStore\Domain\Aggregate\MessageStore $messageStore
     *
     * @return \EmailSender\MessageLog\Domain\Aggregate\MessageLog
     */
    public function add(Message $message, MessageStore $messageStore): MessageLog
    {
        $messageLog   = $this->messageLogBuilder->createFromMessage($message, $messageStore);
        $messageLogId = $this->repositoryWriter->add($messageLog);

        $messageLog->setMessageLogId(new UnsignedInteger($messageLogId));

        return $messageLog;
    }
}
