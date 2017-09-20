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
    const MAX_LENGTH = PHP_INT_MAX;

    /**
     * Min length of the StringLiteralLimit.
     *
     * @var int
     */
    const MIN_LENGTH = 0;

    /**
     * StringLiteralLimit constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->validate($value);

        parent::__construct($value);
    }

    /**
     * @param string $value
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    protected function validate(string $value): void
    {
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
        if (strlen($value) < static::MIN_LENGTH) {
            throw new ValueObjectException(
                'Invalid ' . $this->getClassName() . '. String is too short! Minimum length: ' . static::MIN_LENGTH
            );
        }
    }

    /**
     * @param string $value
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    protected function validateMaxLength(string $value): void
    {
        if (strlen($value) > static::MAX_LENGTH) {
            throw new ValueObjectException(
                'Invalid ' . $this->getClassName() . '. String is too long! Maximum length: ' . static::MAX_LENGTH
            );
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
