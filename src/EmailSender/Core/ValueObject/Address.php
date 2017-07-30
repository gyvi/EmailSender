<?php

namespace EmailSender\Core\ValueObject;

use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteralLimit;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;

/**
 * Class Address
 *
 * @package EmailSender\Core
 */
class Address extends StringLiteralLimit
{
    /**
     * @var int
     */
    protected $minLength = 1;

    /**
     * @var int
     */
    protected $maxLength = 254;

    /**
     * @param string $value
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    protected function validate(string $value): void
    {
        parent::validate($value);

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new ValueObjectException(
                'Invalid ' . $this->getClassName() . '. Wrong email address.'
            );
        }
    }
}
