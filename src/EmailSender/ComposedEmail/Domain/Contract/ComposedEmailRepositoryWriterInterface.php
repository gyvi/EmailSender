<?php

namespace EmailSender\ComposedEmail\Domain\Contract;

use EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail;

/**
 * Interface ComposedEmailRepositoryWriterInterface
 *
 * @package EmailSender\ComposedEmail
 */
interface ComposedEmailRepositoryWriterInterface
{
    /**
     * @param \EmailSender\ComposedEmail\Domain\Aggregate\ComposedEmail $composedEmail
     *
     * @return int
     *
     * @throws \Error
     */
    public function add(ComposedEmail $composedEmail): int;
}
