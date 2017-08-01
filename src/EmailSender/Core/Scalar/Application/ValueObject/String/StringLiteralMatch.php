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
     * Regex pattern of the StringLiteralMatch.
     *
     * @var string
     */
    protected const PATTERN = '/*./';

    /**
     * @param string $value
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    protected function validate(string $value): void
    {
        parent::validate($value);

        if (!empty(static::PATTERN) && !preg_match(static::PATTERN, $value)) {
            throw new ValueObjectException(
                'Invalid ' . $this->getClassName() . '. The given value doesn\'t match with the pattern.'
            );
        }
    }
}
