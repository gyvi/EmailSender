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
    protected const MIN_LENGTH = 1;

    /**
     * Max length of the DisplayName.
     *
     * @var int
     */
    protected const MAX_LENGTH = 64;

    /**
     * Regex pattern of the StringLiteralMatch.
     *
     * @var string
     */
    protected const PATTERN = "/^[\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~\.\\" . '"' . "\(\)\,\:\;\<\>\@\[\\ \]\p{L}]+$/u";
}
