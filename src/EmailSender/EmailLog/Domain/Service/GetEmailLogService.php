<?php

namespace EmailSender\EmailLog\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface;

/**
 * Class GetEmailLogService
 *
 * @package EmailSender\EmailLog\Domain\Service
 */
class GetEmailLogService
{
    /**
     * @var \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface
     */
    private $repositoryReader;

    /**
     * GetEmailLogService constructor.
     *
     * @param \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryReaderInterface $repositoryReader
     */
    public function __construct(EmailLogRepositoryReaderInterface $repositoryReader)
    {
        $this->repositoryReader = $repositoryReader;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $emailLogId
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     */
    public function get(UnsignedInteger $emailLogId): EmailLog
    {
        return $this->repositoryReader->get($emailLogId);
    }
}
