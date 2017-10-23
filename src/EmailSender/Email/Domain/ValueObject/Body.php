<?php

namespace EmailSender\Email\Domain\ValueObject;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteralLimit;

/**
 * Class Body
 *
 * @package EmailSender\Email
 */
class Body extends StringLiteralLimit
{
    /**
     * @var int
     */
    const MIN_LENGTH = 1;
}
