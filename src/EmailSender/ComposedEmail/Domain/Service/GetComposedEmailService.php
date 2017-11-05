<?php

namespace EmailSender\ComposedEmail\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface;
use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;

/**
 * Class GetComposedEmailService
 *
 * @package EmailSender\ComposedEmail
 */
class GetComposedEmailService
{
    /**
     * @var \EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface
     */
    private $repositoryReader;

    /**
     * GetComposedEmailService constructor.
     *
     * @param \EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface $repositoryReader
     */
    public function __construct(ComposedEmailRepositoryReaderInterface $repositoryReader)
    {
        $this->repositoryReader = $repositoryReader;
    }

    /**
     * @param \EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger $composedEmailId
     *
     * @return \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail
     * @throws \Error
     * @throws \InvalidArgumentException
     */
    public function get(UnsignedInteger $composedEmailId): ComposedEmail
    {
        return $this->repositoryReader->get($composedEmailId);
    }
}
