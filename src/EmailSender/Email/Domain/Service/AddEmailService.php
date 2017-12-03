<?php

namespace EmailSender\Email\Domain\Service;

use EmailSender\ComposedEmail\Application\Service\ComposedEmailService;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\Email\Domain\Factory\EmailFactory;
use EmailSender\Core\Catalog\EmailStatusList;
use EmailSender\EmailLog\Application\Service\EmailLogService;
use EmailSender\Core\ValueObject\EmailStatus;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailQueue\Application\Service\EmailQueueService;
use Throwable;

/**
 * Class AddEmailService
 *
 * @package EmailSender\Email
 */
class AddEmailService
{
    /**
     * @var \EmailSender\ComposedEmail\Application\Service\ComposedEmailService
     */
    private $composedEmailService;

    /**
     * @var \EmailSender\EmailLog\Application\Service\EmailLogService
     */
    private $emailLogService;

    /**
     * @var \EmailSender\EmailQueue\Application\Service\EmailQueueService
     */
    private $emailQueueService;

    /**
     * @var \EmailSender\Email\Domain\Factory\EmailFactory
     */
    private $emailFactory;

    /**
     * AddEmailService constructor.
     *
     * @param \EmailSender\ComposedEmail\Application\Service\ComposedEmailService $composedEmailService
     * @param \EmailSender\EmailLog\Application\Service\EmailLogService           $emailLogService
     * @param \EmailSender\EmailQueue\Application\Service\EmailQueueService       $emailQueueService
     * @param \EmailSender\Email\Domain\Factory\EmailFactory                      $emailFactory
     */
    public function __construct(
        ComposedEmailService $composedEmailService,
        EmailLogService $emailLogService,
        EmailQueueService $emailQueueService,
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
     * @return int
     * @throws \EmailSender\ComposedEmail\Application\Exception\ComposedEmailException
     * @throws \EmailSender\EmailLog\Application\Exception\EmailLogException
     * @throws \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     * @throws \InvalidArgumentException
     * @throws \Throwable
     */
    public function add(array $request): int
    {
        $email         = $this->emailFactory->create($request);
        $composedEmail = $this->composedEmailService->add($email);
        $emailLog      = $this->emailLogService->add($email, $composedEmail);

        if ($email->getDelay()->getValue() > 0) {
            return $this->addToQueue($emailLog);
        }

        return $this->send($composedEmail, $emailLog);
    }

    /**
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog $emailLog
     *
     * @return int
     *
     * @throws \EmailSender\EmailLog\Application\Exception\EmailLogException
     * @throws \EmailSender\EmailQueue\Application\Exception\EmailQueueException
     * @throws \Throwable
     */
    private function addToQueue(EmailLog $emailLog): int
    {
        try {
            $this->emailQueueService->add($emailLog);

            $this->emailLogService->setStatus(
                $emailLog->getEmailLogId(),
                new EmailStatus(EmailStatusList::STATUS_QUEUED),
                ''
            );

            return EmailStatusList::STATUS_QUEUED;
        } catch (Throwable $e) {
            $this->emailLogService->setStatus(
                $emailLog->getEmailLogId(),
                new EmailStatus(EmailStatusList::STATUS_ERROR),
                $e->getMessage()
            );

            throw $e;
        }
    }

    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     * @param \EmailSender\EmailLog\Domain\Aggregate\EmailLog           $emailLog
     *
     * @return int
     *
     * @throws \EmailSender\EmailLog\Application\Exception\EmailLogException
     * @throws \Throwable
     */
    private function send(ComposedEmail $composedEmail, EmailLog $emailLog): int
    {
        try {
            $this->composedEmailService->send($composedEmail);

            $this->emailLogService->setStatus(
                $emailLog->getEmailLogId(),
                new EmailStatus(EmailStatusList::STATUS_SENT),
                ''
            );

            return EmailStatusList::STATUS_SENT;
        } catch (Throwable $e) {
            $this->emailLogService->setStatus(
                $emailLog->getEmailLogId(),
                new EmailStatus(EmailStatusList::STATUS_ERROR),
                $e->getMessage()
            );

            throw $e;
        }
    }
}
