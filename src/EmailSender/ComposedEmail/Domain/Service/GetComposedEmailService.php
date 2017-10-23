<?php

namespace EmailSender\ComposedEmail\Domain\Service;

use EmailSender\Core\Scalar\Application\ValueObject\Numeric\UnsignedInteger;
use EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory;
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
     * @var \EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory
     */
    private $composedEmailFactory;

    /**
     * GetComposedEmailService constructor.
     *
     * @param \EmailSender\ComposedEmail\Domain\Contract\ComposedEmailRepositoryReaderInterface $repositoryReader
     * @param \EmailSender\ComposedEmail\Domain\Factory\ComposedEmailFactory                    $composedEmailFactory
     */
    public function __construct(
        ComposedEmailRepositoryReaderInterface $repositoryReader,
        ComposedEmailFactory $composedEmailFactory
    ) {
        $this->repositoryReader     = $repositoryReader;
        $this->composedEmailFactory = $composedEmailFactory;
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
        $composedEmailArray = $this->repositoryReader->get($composedEmailId);

        return $this->composedEmailFactory->createFromArray($composedEmailArray);
    }
}
