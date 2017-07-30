<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\String;

use EmailSender\Core\Scalar\Application\Contract\ValueObjectInterface;

/**
 * Class StringLiteral
 *
 * @package EmailSender\Core\Scalar\Application\ValueObject\String
 */
class StringLiteral implements ValueObjectInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * StringLiteral constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->validate($value);

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Validates value.
     *
     * @param string $value
     */
    protected function validate(string $value): void
    {
    }
}
