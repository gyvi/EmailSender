<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\Numeric;

use EmailSender\Core\Scalar\Application\Contract\ValueObjectInterface;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;

class UnsignedInteger implements ValueObjectInterface
{
    /**
     * Lower limit of the UnsignedInteger.
     */
    const LIMIT_LOWER = 0;

    /**
     * Upper limit of the UnsignedInteger.
     */
    const LIMIT_UPPER = PHP_INT_MAX;

    /**
     * @var int
     */
    private $value;

    /**
     * UnsignedInteger constructor.
     *
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->validate($value);

        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    protected function validate(int $value)
    {
        if ($value > static::LIMIT_UPPER || $value < static::LIMIT_LOWER) {
            throw new ValueObjectException('Invalid ' .  $this->getClassName() . '. Integer is out of range.');
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
