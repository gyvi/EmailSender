<?php

namespace EmailSender\EmailQueue\Domain\Service;

use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
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
     * @return \EmailSender\Core\ValueObject\EmailStatus
     */
    public function add(EmailLog $emailLog): EmailStatus
    {
        $emailQueue = $this->emailQueueFactory->create($emailLog);

        return $this->queueWriter->add($emailQueue);
    }
}
