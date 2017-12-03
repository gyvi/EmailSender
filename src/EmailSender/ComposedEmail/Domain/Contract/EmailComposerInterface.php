<?php

namespace EmailSender\ComposedEmail\Domain\Contract;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;
use EmailSender\Email\Domain\Aggregate\Email;

/**
 * Class EmailBuilderInterface
 *
 * @package EmailSender\ComposedEmail
 */
interface EmailComposerInterface
{
    /**
     * @param \EmailSender\Email\Domain\Aggregate\Email $email
     *
     * @return \EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral
     */
    public function compose(Email $email): StringLiteral;
}
