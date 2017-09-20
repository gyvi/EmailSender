<?php

namespace EmailSender\Core\ValueObject;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteralLimit;

/**
 * Class Subject
 *
 * @package EmailSender\Message
 */
class Subject extends StringLiteralLimit
{
    /**
     * Max length of the Subject.
     *
     * @var int
     */
    const MAX_LENGTH = 77;

    /**
     * Min length of the Subject.
     *
     * @var int
     */
    const MIN_LENGTH = 0;
}
