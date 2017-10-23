<?php

namespace EmailSender\EmailQueue\Domain\Service;

use EmailSender\EmailLog\Application\Catalog\EmailLogStatuses;
use EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface;
use EmailSender\EmailLog\Application\ValueObject\EmailLogStatus;
use EmailSender\EmailQueue\Application\Catalog\EmailQueuePropertyNames;
use EmailSender\EmailQueue\Domain\Contract\SMTPSenderInterface;
use EmailSender\EmailQueue\Infrastructure\Service\SMTPException;
use EmailSender\ComposedEmail\Application\Contract\ComposedEmailServiceInterface;
use Throwable;

/**
 * Class SendEmailService
 *
 * @package EmailSender\EmailQueue
 */
class SendEmailService
{
    /**
     * @var \EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface
     */
    private $emailLogService;

    /**
     * @var \EmailSender\ComposedEmail\Application\Contract\ComposedEmailServiceInterface
     */
    private $composedEmailService;

    /**
     * @var \EmailSender\EmailQueue\Domain\Contract\SMTPSenderInterface
     */
    private $smtpSender;

    /**
     * SendEmailService constructor.
     *
     * @param \EmailSender\EmailLog\Application\Contract\EmailLogServiceInterface           $emailLogService
     * @param \EmailSender\ComposedEmail\Application\Contract\ComposedEmailServiceInterface $composedEmailService
     * @param \EmailSender\EmailQueue\Domain\Contract\SMTPSenderInterface                   $smtpSender
     */
    public function __construct(
        EmailLogServiceInterface $emailLogService,
        ComposedEmailServiceInterface $composedEmailService,
        SMTPSenderInterface $smtpSender
    ) {
        $this->emailLogService      = $emailLogService;
        $this->composedEmailService = $composedEmailService;
        $this->smtpSender           = $smtpSender;
    }

    /**
     * @param string $emailQueueJson
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \EmailSender\EmailQueue\Infrastructure\Service\SMTPException
     * @throws \Error
     * @throws \InvalidArgumentException
     */
    public function send(string $emailQueueJson): void
    {
        $emailQueue = json_decode($emailQueueJson, true);

        $emailLog = $this->emailLogService->get(
            $emailQueue[EmailQueuePropertyNames::EMAIL_LOG_ID]
        );

        $composedEmail = $this->composedEmailService->get($emailLog->getComposedEmailId());

        try {
            $this->smtpSender->send($emailLog, $composedEmail);

            $this->emailLogService->setStatus(
                $emailLog->getEmailLogId(),
                new EmailLogStatus(EmailLogStatuses::STATUS_SENT),
                ''
            );
        } catch (Throwable $e) {
            $this->emailLogService->setStatus(
                $emailLog->getEmailLogId(),
                new EmailLogStatus(EmailLogStatuses::STATUS_ERROR),
                $e->getMessage()
            );

            throw new SMTPException($e->getMessage(), 0, $e);
        }
    }
}
