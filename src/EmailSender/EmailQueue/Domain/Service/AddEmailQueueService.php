<?php

namespace EmailSender\EmailQueue\Domain\Service;

use EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface;
use EmailSender\Email\Application\Contract\EmailServiceInterface;
use EmailSender\EmailLog\Application\Catalog\EmailLogStatuses;
use EmailSender\EmailLog\Application\ValueObject\EmailLogStatus;
use EmailSender\EmailQueue\Domain\Aggregator\EmailQueue;
use EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory;
use EmailSender\EmailQueue\Domain\Contract\EmailQueueRepositoryWriterInterface;
use EmailSender\ComposedEmail\Application\Contract\ComposedEmailServiceInterface;

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
     * @var \EmailSender\Email\Application\Contract\EmailServiceInterface
     */
    private $emailService;

    /**
     * @var \EmailSender\ComposedEmail\Application\Contract\ComposedEmailServiceInterface
     */
    private $composedEmailService;

    /**
     * @var \EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface
     */
    private $emailLogService;

    /**
     * @var \EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory
     */
    private $emailQueueFactory;

    /**
     * AddEmailQueueService constructor.
     *
     * @param \EmailSender\EmailQueue\Domain\Contract\EmailQueueRepositoryWriterInterface   $queueWriter
     * @param \EmailSender\Email\Application\Contract\EmailServiceInterface                 $emailService
     * @param \EmailSender\ComposedEmail\Application\Contract\ComposedEmailServiceInterface $composedEmailService
     * @param \EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface           $emailLogService
     * @param \EmailSender\EmailQueue\Domain\Factory\EmailQueueFactory                      $emailQueueFactory
     */
    public function __construct(
        EmailQueueRepositoryWriterInterface $queueWriter,
        EmailServiceInterface $emailService,
        ComposedEmailServiceInterface $composedEmailService,
        EmailLogServiceInterface $emailLogService,
        EmailQueueFactory $emailQueueFactory
    ) {
        $this->queueWriter          = $queueWriter;
        $this->emailService         = $emailService;
        $this->composedEmailService = $composedEmailService;
        $this->emailLogService      = $emailLogService;
        $this->emailQueueFactory    = $emailQueueFactory;
    }

    /**
     * @param array $request
     *
     * @return \EmailSender\EmailQueue\Domain\Aggregator\EmailQueue
     * @throws \Error
     * @throws \InvalidArgumentException
     * @throws \phpmailerException
     */
    public function add(array $request): EmailQueue
    {
        $email         = $this->emailService->getEmailFromRequest($request);
        $composedEmail = $this->composedEmailService->add($email);
        $emailLog      = $this->emailLogService->add($email, $composedEmail);

        $emailQueue    = $this->emailQueueFactory->create($emailLog);

        $this->queueWriter->add($emailQueue);

        $this->emailLogService->setStatus(
            $emailQueue->getComposedEmailId(),
            new EmailLogStatus(EmailLogStatuses::STATUS_QUEUED),
            ''
        );

        return $emailQueue;
    }
}
