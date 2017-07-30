<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\String;

use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;

/**
 * Class StringLiteralMatch
 *
 * @package EmailSender\Core\Scalar
 */
abstract class StringLiteralMatch extends StringLiteralLimit
{
    /**
     * Regex pattern of the valid string
     *
     * @var string
     */
    protected $pattern;

    /**
     * @param string $value
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    protected function validate(string $value): void
    {
        parent::validate($value);

        if (!empty($this->pattern) && !preg_match($this->pattern, $value)) {
            throw new ValueObjectException(
                'Invalid ' . $this->getClassName() . '. The given value doesn\'t match with the pattern.'
            );
        }
    }
}
