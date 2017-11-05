<?php

namespace EmailSender\Core\ValueObject;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteralMatch;

/**
 * Class DisplayName
 *
 * @package EmailSender\Core
 */
class DisplayName extends StringLiteralMatch
{
    /**
     * Min length of the DisplayName.
     *
     * @var int
     */
    const MIN_LENGTH = 1;

    /**
     * Max length of the DisplayName.
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
