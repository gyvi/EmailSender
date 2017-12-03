<?php

namespace EmailSender\EmailQueue\Domain\Service;

use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;
use EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory;
use EmailSender\EmailQueue\Domain\Contract\EmailQueueRepositoryWriterInterface;

/**
 * Class AddEmailQueueService
 *
 * @package EmailSender\EmailQueue
 */
class AddEmailQueueService
{
    /**
     * @var \EmailSender\EmailQueue\Domain\Contract\EmailQueueRepositoryWriterInterface
     */
    private $queueWriter;

    /**
     * @var \EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory
     */
    private $emailQueueFactory;

    /**
     * AddEmailQueueService constructor.
     *
     * @param \EmailSender\EmailQueue\Domain\Contract\EmailQueueRepositoryWriterInterface   $queueWriter
     * @param \EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory                      $emailQueueFactory
     */
    public function __construct(
        EmailQueueRepositoryWriterInterface $queueWriter,
        EmailQueueFactory $emailQueueFactory
    ) {
        $this->queueWriter       = $queueWriter;
        $this->emailQueueFactory = $emailQueueFactory;
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog $emailLog
     *
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue
     */
    public function add(EmailLog $emailLog): EmailQueue
    {
        $emailQueue = $this->emailQueueFactory->create($emailLog);

        $this->queueWriter->add($emailQueue);

        return $emailQueue;
    }
}
