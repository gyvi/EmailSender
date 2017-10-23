<?php

namespace EmailSender\EmailLog\Domain\Factory;

use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Core\Scalar\Application\Factory\DateTimeFactory;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\SignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Core\ValueObject\Subject;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\EmailLog\Domain\Aggregate\EmailLog;
use EmailSender\EmailLog\Infrastructure\Persistence\EmailLogFieldList;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;

/**
 * Class EmailLogFactory
 *
 * @package EmailSender\EmailLog
 */
class EmailLogFactory
{
    /**
     * @var \EmailSender\Core\Factory\RecipientsFactory
     */
    private $recipientsFactory;

    /**
     * @var \EmailSender\Core\Factory\EmailAddressFactory
     */
    private $emailAddressFactory;

    /**
     * @var \EmailSender\Core\Scalar\Application\Factory\DateTimeFactory
     */
    private $dateTimeFactory;

    /**
     * EmailLogFactory constructor.
     *
     * @param \EmailSender\Core\Factory\RecipientsFactory                  $recipientsFactory
     * @param \EmailSender\Core\Factory\EmailAddressFactory                $emailAddressFactory
     * @param \EmailSender\Core\Scalar\Application\Factory\DateTimeFactory $dateTimeFactory
     */
    public function __construct(
        RecipientsFactory $recipientsFactory,
        EmailAddressFactory $emailAddressFactory,
        DateTimeFactory $dateTimeFactory
    ) {
        $this->recipientsFactory   = $recipientsFactory;
        $this->emailAddressFactory = $emailAddressFactory;
        $this->dateTimeFactory     = $dateTimeFactory;
    }

    /**
     * @param \EmailSender\Email\Domain\Aggregate\Email               $email
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     */
    public function create(Email $email, ComposedEmail $composedEmail): EmailLog
    {
        return new EmailLog(
            $composedEmail->getComposedEmailId(),
            $email->getFrom(),
            $composedEmail->getRecipients(),
            $email->getSubject(),
            $email->getDelay()
        );
    }

    /**
     * @param array $emailLogArray
     *
     * @return \EmailSender\EmailLog\Domain\Aggregate\EmailLog
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     * @throws \InvalidArgumentException
     */
    public function createFromArray(array $emailLogArray): EmailLog
    {
        $emailLog = new EmailLog(
            new UnsignedInteger($emailLogArray[EmailLogFieldList::COMPOSED_EMAIL_ID]),
            $this->emailAddressFactory->create($emailLogArray[EmailLogFieldList::FROM]),
            $this->recipientsFactory->createFromArray(
                json_decode($emailLogArray[EmailLogFieldList::RECIPIENTS], true)
            ),
            new Subject($emailLogArray[EmailLogFieldList::SUBJECT]),
            new UnsignedInteger($emailLogArray[EmailLogFieldList::DELAY])
        );

        $emailLog->setEmailLogId(new UnsignedInteger($emailLogArray[EmailLogFieldList::EMAIL_LOG_ID]));
        $emailLog->setStatus(new SignedInteger((int)$emailLogArray[EmailLogFieldList::STATUS]));
        $emailLog->setLogged(
            $this->dateTimeFactory->createFromDateTime(new \DateTime($emailLogArray[EmailLogFieldList::LOGGED]))
        );

        if (!empty($emailLogArray[EmailLogFieldList::QUEUED])) {
            $emailLog->setQueued(
                $this->dateTimeFactory->createFromDateTime(new \DateTime($emailLogArray[EmailLogFieldList::QUEUED]))
            );
        }

        if (!empty($emailLogArray[EmailLogFieldList::SENT])) {
            $emailLog->setSent(
                $this->dateTimeFactory->createFromDateTime(new \DateTime($emailLogArray[EmailLogFieldList::SENT]))
            );
        }

        if (!empty($emailLogArray[EmailLogFieldList::ERROR_MESSAGE])) {
            $emailLog->setErrorMessage(new StringLiteral($emailLogArray[EmailLogFieldList::ERROR_MESSAGE]));
        }

        return $emailLog;
    }
}
