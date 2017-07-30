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
     * @var int
     */
    protected $minLength = 1;

    /**
     * @var int
     */
    protected $maxLength = 64;

    /**
     * @var string
     */
    protected $pattern = "/^[-\'\.\, \p{L}]+$/u";
}
