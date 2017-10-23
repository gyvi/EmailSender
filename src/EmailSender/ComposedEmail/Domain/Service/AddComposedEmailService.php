<?php

namespace EmailSender\ComposedEmail\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;
use EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory;
use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryWriterInterface;
use EmailSender\Email\Domain\Aggregate\Email;

/**
 * Class AddComposedEmailService
 *
 * @package EmailSender\ComposedEmail
 */
class AddComposedEmailService
{
    /**
     * @var \EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryWriterInterface
     */
    private $repositoryWriter;

    /**
     * @var \EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory
     */
    private $composedEmailFactory;

    /**
     * AddComposedEmailService constructor.
     *
     * @param \EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryWriterInterface $repositoryWriter
     * @param \EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory                    $composedEmailFactory
     */
    public function __construct(
        ComposedEmailRepositoryWriterInterface $repositoryWriter,
        ComposedEmailFactory $composedEmailFactory
    ) {
        $this->repositoryWriter     = $repositoryWriter;
        $this->composedEmailFactory = $composedEmailFactory;
    }

    /**
     * @param \EmailSender\Email\Domain\Aggregate\Email $email
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     *
     * @throws \Error
     * @throws \InvalidArgumentException
     * @throws \phpmailerException
     */
    public function add(Email $email): ComposedEmail
    {
        $composedEmail   = $this->composedEmailFactory->create($email);
        $composedEmailId = $this->repositoryWriter->add($composedEmail);

        $composedEmail->setComposedEmailId(new UnsignedInteger($composedEmailId));

        return $composedEmail;
    }
}
