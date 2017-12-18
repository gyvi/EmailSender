<?php

namespace EmailSender\Email\Domain\Service;

use EmailSender\ComposedEmail\Application\Contract\ComposedEmailServiceInterface;
use EmailSender\ComposedEmail\Application\Exception\ComposedEmailException;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\Email\Domain\Factory\EmailFactory;
use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface;
use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailQueue\Application\Contract\EmailQueueServiceInterface;
use EmailSender\EmailQueue\Application\Exception\EmailQueueException;
use EmailSender\Email\Domain\Aggregate\Email;

/**
 * Class AddEmailService
 *
 * @package EmailSender\Email
 */
class AddEmailService
{
    /**
     * @var \EmailSender\ComposedEmail\Application\Contract\ComposedEmailServiceInterface
     */
    private $composedEmailService;

    /**
     * @var \EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface
     */
    private $emailLogService;

    /**
     * @var \EmailSender\EmailQueue\Application\Contract\EmailQueueServiceInterface
     */
    private $emailQueueService;

    /**
     * @var \EmailSender\Email\Domain\Factory\EmailFactory
     */
    private $emailFactory;

    /**
     * AddEmailService constructor.
     *
     * @param \EmailSender\ComposedEmail\Application\Contract\ComposedEmailServiceInterface $composedEmailService
     * @param \EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface           $emailLogService
     * @param \EmailSender\EmailQueue\Application\Contract\EmailQueueServiceInterface       $emailQueueService
     * @param \EmailSender\Email\Domain\Factory\EmailFactory                                $emailFactory
     */
    public function __construct(
        ComposedEmailServiceInterface $composedEmailService,
        EmailLogServiceInterface $emailLogService,
        EmailQueueServiceInterface $emailQueueService,
        EmailFactory $emailFactory
    ) {
        $this->composedEmailService = $composedEmailService;
        $this->emailLogService      = $emailLogService;
        $this->emailQueueService    = $emailQueueService;
        $this->emailFactory         = $emailFactory;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\Email\Domain\Aggregate\Email
     *
     * @throws \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     * @throws \EmailSender\EmailLog\Application\Exception\EmailLogException
     * @throws \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     * @throws \InvalidArgumentException
     */
    public function add(array $request): Email
    {
        $email         = $this->emailFactory->create($request);
        $composedEmail = $this->composedEmailService->add($email);
        $emailLog      = $this->emailLogService->add($email, $composedEmail);

        if ($email->getDelay()->getValue() > 0) {
            $email->setEmailStatus($this->addToQueue($emailLog));

            return $email;
        }

        $email->setEmailStatus($this->send($composedEmail, $emailLog));

        return $email;
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog $emailLog
     *
     * @return \EmailSender\Core\ValueObject\EmailStatus
     *
     * @throws \EmailSender\EmailLog\Application\Exception\EmailLogException
     * @throws \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     */
    private function addToQueue(EmailLog $emailLog): EmailStatus
    {
        try {
            $emailStatus = $this->emailQueueService->add($emailLog);
        } catch (EmailQueueException $e) {
            $this->emailLogService->setStatus(
                $emailLog->getEmailLogId(),
                new EmailStatus(EmailStatusList::ERROR),
                $e->getMessage()
            );

            throw $e;
        }

        $this->emailLogService->setStatus(
            $emailLog->getEmailLogId(),
            $emailStatus,
            ''
        );

        return $emailStatus;
    }

    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog           $emailLog
     *
     * @return \EmailSender\Core\ValueObject\EmailStatus
     *
     * @throws \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     * @throws \EmailSender\EmailLog\Application\Exception\EmailLogException
     */
    private function send(ComposedEmail $composedEmail, EmailLog $emailLog): EmailStatus
    {
        try {
            $emailStatus = $this->composedEmailService->send($composedEmail);
        } catch (ComposedEmailException $e) {
            $this->emailLogService->setStatus(
                $emailLog->getEmailLogId(),
                new EmailStatus(EmailStatusList::ERROR),
                $e->getMessage()
            );

            throw $e;
        }

        $this->emailLogService->setStatus(
            $emailLog->getEmailLogId(),
            $emailStatus,
            ''
        );

        return $emailStatus;
    }
}
