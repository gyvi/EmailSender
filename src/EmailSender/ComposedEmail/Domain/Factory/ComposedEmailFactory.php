<?php

namespace EmailSender\ComposedEmail\Domain\Factory;

use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\ComposedEmail\Domain\Contract\EmailComposerInterface;
use EmailSender\ComposedEmail\Infrastructure\Persistence\ComposedEmailFieldList;

/**
 * Class ComposedEmailFactory
 *
 * @package EmailSender\ComposedEmail
 */
class ComposedEmailFactory
{
    /**
     * @var \EmailSender\ComposedEmail\Domain\Contract\EmailComposerInterface
     */
    private $emailComposer;

    /**
     * @var \EmailSender\Core\Factory\RecipientsFactory
     */
    private $recipientsFactory;

    /**
     * ComposedEmailFactory constructor.
     *
     * @param \EmailSender\ComposedEmail\Domain\Contract\EmailComposerInterface $emailComposer
     * @param \EmailSender\Core\Factory\RecipientsFactory                      $recipientsFactory
     */
    public function __construct(EmailComposerInterface $emailComposer, RecipientsFactory $recipientsFactory)
    {
        $this->emailComposer     = $emailComposer;
        $this->recipientsFactory = $recipientsFactory;
    }

    /**
     * @param \EmailSender\Email\Domain\Aggregate\Email $email
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \InvalidArgumentException
     * @throws \phpmailerException
     */
    public function create(Email $email): ComposedEmail
    {
        $recipients          = $this->recipientsFactory->create($email);
        $composedEmailString = $this->emailComposer->compose($email);

        return new ComposedEmail($recipients, $composedEmailString);
    }

    /**
     * @param array $composedEmailArray
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \InvalidArgumentException
     */
    public function createFromArray(array $composedEmailArray): ComposedEmail
    {
        $recipients = $this->recipientsFactory->createFromArray(
            json_decode($composedEmailArray[ComposedEmailFieldList::RECIPIENTS], true)
        );

        $composedEmail = new ComposedEmail(
            $recipients,
            new StringLiteral($composedEmailArray[ComposedEmailFieldList::EMAIL])
        );

        $composedEmail->setComposedEmailId(
            new UnsignedInteger($composedEmailArray[ComposedEmailFieldList::COMPOSED_EMAIL_ID])
        );

        return $composedEmail;
    }
}
