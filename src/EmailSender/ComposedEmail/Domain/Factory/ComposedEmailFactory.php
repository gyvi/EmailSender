<?php

namespace EmailSender\ComposedEmail\Domain\Factory;

use EmailSender\ComposedEmail\Application\Catalog\ComposedEmailPropertyNameList;
use EmailSender\Core\Factory\EmailAddressFactory;
use EmailSender\Core\Factory\RecipientsFactory;
use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Email\Domain\Aggregate\Email;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\ComposedEmail\Domain\Contract\EmailComposerInterface;

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
     * @var \EmailSender\Core\Factory\EmailAddressFactory
     */
    private $emailAddressFactory;

    /**
     * ComposedEmailFactory constructor.
     *
     * @param \EmailSender\ComposedEmail\Domain\Contract\EmailComposerInterface $emailComposer
     * @param \EmailSender\Core\Factory\RecipientsFactory                       $recipientsFactory
     * @param \EmailSender\Core\Factory\EmailAddressFactory                     $emailAddressFactory
     */
    public function __construct(
        EmailComposerInterface $emailComposer,
        RecipientsFactory $recipientsFactory,
        EmailAddressFactory $emailAddressFactory
    ) {
        $this->emailComposer       = $emailComposer;
        $this->recipientsFactory   = $recipientsFactory;
        $this->emailAddressFactory = $emailAddressFactory;
    }

    /**
     * @param \EmailSender\Email\Domain\Aggregate\Email $email
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \InvalidArgumentException
     */
    public function create(Email $email): ComposedEmail
    {
        $recipients          = $this->recipientsFactory->create($email);
        $composedEmailString = $this->emailComposer->compose($email);

        return new ComposedEmail($email->getFrom(), $recipients, $composedEmailString);
    }

    /**
     * @param array $composedEmailArray
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     *
     * @throws \InvalidArgumentException
     */
    public function createFromArray(array $composedEmailArray): ComposedEmail
    {
        $recipients = $this->recipientsFactory->createFromArray(
            json_decode($composedEmailArray[ComposedEmailPropertyNameList::RECIPIENTS], true)
        );

        $composedEmail = new ComposedEmail(
            $this->emailAddressFactory->create($composedEmailArray[ComposedEmailPropertyNameList::FROM]),
            $recipients,
            new StringLiteral($composedEmailArray[ComposedEmailPropertyNameList::EMAIL])
        );

        $composedEmail->setComposedEmailId(
            new UnsignedInteger($composedEmailArray[ComposedEmailPropertyNameList::COMPOSED_EMAIL_ID])
        );

        return $composedEmail;
    }
}
