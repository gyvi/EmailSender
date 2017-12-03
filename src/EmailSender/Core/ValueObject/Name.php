<?php

namespace EmailSender\Core\ValueObject;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteralMatch;

/**
 * Class Name
 *
 * @package EmailSender\Core
 */
class Name extends StringLiteralMatch
{
    /**
     * Min length of the Name.
     *
     * @var int
     */
    const MIN_LENGTH = 1;

    /**
     * Max length of Name.
     *
     * @var int
     */
    const MAX_LENGTH = 64;

    /**
     * Regex pattern of the StringLiteralMatch.
     *
     * @var string
     */
    const PATTERN = "/^[\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~\.\\" . '"' . "\(\)\,\:\;\<\>\@\[\\ \]\p{L}]+$/u";

     /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}
