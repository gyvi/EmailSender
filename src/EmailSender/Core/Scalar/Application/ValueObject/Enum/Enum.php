<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\Enum;

use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use EmailSender\Core\Scalar\Application\ValueObject\String\StringLiteral;

/**
 * Class Enum
 *
 * @package EmailSender\Core\Scalar
 */
abstract class Enum extends StringLiteral
{
    /**
     * List of enabled values.
     *
     * @var array
     */
    protected $enabledValues = [];

    /**
     * Enum constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct($value);

        $this->validate($value);
    }

    /**
     * @param string $value
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    public function validate(string $value): void
    {
        if (!in_array($value, $this->enabledValues, true)) {
            throw new ValueObjectException('Invalid ' . $this->getClassName() . '.');
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
