<?php

namespace EmailSender\EmailQueue\Domain\Factory;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailQueue\Application\Catalog\EmailQueuePropertyNamesList;
use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;

/**
 * Class EmailQueueFactory
 *
 * @package EmailSender\EmailQueue
 */
class EmailQueueFactory
{
    /**
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog $emailLog
     *
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue
     */
    public function create(EmailLog $emailLog): EmailQueue
    {
        return new EmailQueue($emailLog->getEmailLogId(), $emailLog->getComposedEmailId(), $emailLog->getDelay());
    }

    /**
     * @param array $emailQueueArray
     *
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue
     */
    public function createFromArray(array $emailQueueArray): EmailQueue
    {
        return new EmailQueue(
            new UnsignedInteger($emailQueueArray[EmailQueuePropertyNamesList::EMAIL_LOG_ID]),
            new UnsignedInteger($emailQueueArray[EmailQueuePropertyNamesList::COMPOSED_EMAIL_ID]),
            new UnsignedInteger($emailQueueArray[EmailQueuePropertyNamesList::DELAY])
        );
    }
}
