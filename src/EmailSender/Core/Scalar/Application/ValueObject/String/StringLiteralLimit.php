<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\String;

use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;

/**
 * Class StringLiteralLimit
 *
 * @package EmailSender\Core\Scalar
 */
abstract class StringLiteralLimit extends StringLiteral
{
    /**
     * Max length of the StringLiteralLimit.
     *
     * @var int
     */
    protected $maxLength;

    /**
     * Min length of the StringLiteralLimit.
     *
     * @var int
     */
    protected $minLength;

    /**
     * @param string $value
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    protected function validate(string $value): void
    {
        parent::validate($value);

        $this->validateMinLength($value);
        $this->validateMaxLength($value);
    }

    /**
     * @param string $value
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    protected function validateMinLength(string $value): void
    {
        if (!empty($this->minLength) && strlen($value) < $this->minLength) {
            throw new ValueObjectException('Invalid ' . $this->getClassName() . '. String is too short!');
        }
    }

    /**
     * @param string $value
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    protected function validateMaxLength(string $value): void
    {
        if (!empty($this->maxLength) && strlen($value) > $this->maxLength) {
            throw new ValueObjectException('Invalid ' . $this->getClassName() . '. String is too long!');
        }
    }

    /**
     * @return string
     */
    protected function getClassName(): string
    {
        $fullQualifiedNameArray = explode('\\', static::class);

        return end($fullQualifiedNameArray);
    }
}
