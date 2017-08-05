<?php

namespace EmailSender\Message\Domain\ValueObject;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteralLimit;

/**
 * Class Body
 *
 * @package EmailSender\Message\Domain\ValueObject
 */
class Body extends StringLiteralLimit
{
    /**
     * @var int
     */
    protected const MIN_LENGTH = 1;
}
