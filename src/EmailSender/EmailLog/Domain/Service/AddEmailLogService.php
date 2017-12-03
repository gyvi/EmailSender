<?php

namespace EmailSender\EmailLog\Domain\Service;

use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryWriterInterface;
use EmailSender\EmailLog\Domain\Factory\EmailLogFactory;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;

/**
 * Class AddEmailLogService
 *
 * @package EmailSender\EmailLog
 */
class AddEmailLogService
{
    /**
     * @var \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryWriterInterface
     */
    private $repositoryWriter;

    /**
     * @var \EmailSender\EmailLog\Domain\Factory\EmailLogFactory
     */
    private $emailLogFactory;

    /**
     * AddEmailLogService constructor.
     *
     * @param \EmailSender\EmailLog\Domain\Contract\EmailLogRepositoryWriterInterface $repositoryWriter
     * @param \EmailSender\EmailLog\Domain\Factory\EmailLogFactory                    $emailLogFactory
     */
    public function __construct(
        EmailLogRepositoryWriterInterface $repositoryWriter,
        EmailLogFactory $emailLogFactory
    ) {
        $this->repositoryWriter  = $repositoryWriter;
        $this->emailLogFactory   = $emailLogFactory;
    }

    /**
     * @param \EmailSender\Email\Domain\Aggregate\Email                 $email
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     */
    public function add(Email $email, ComposedEmail $composedEmail): EmailLog
    {
        $emailLog   = $this->emailLogFactory->create($email, $composedEmail);
        $emailLogId = $this->repositoryWriter->add($emailLog);

        $emailLog->setEmailLogId($emailLogId);

        return $emailLog;
    }
}
