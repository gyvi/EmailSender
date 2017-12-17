<?php

namespace EmailSender\EmailQueue\Domain\Service;

use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;
use EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory;

/**
 * Class GetEmailQueueService
 *
 * @package EmailSender\EmailQueue
 */
class GetEmailQueueService
{
    /**
     * @var \EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory
     */
    private $emailQueueFactory;

    /**
     * GetEmailQueueService constructor.
     *
     * @param \EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory $emailQueueFactory
     */
    public function __construct(EmailQueueFactory $emailQueueFactory)
    {
        $this->emailQueueFactory = $emailQueueFactory;
    }

    /**
     * @param array $emailQueueArray
     *
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue
     */
    public function get(array $emailQueueArray): EmailQueue
    {
        return $this->emailQueueFactory->createFromArray($emailQueueArray);
    }
}
