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
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     *
     * @throws \EmailSender\Core\Scalar\Application\Exception\ValueObjectException
     */
    public function validate(int $value)
    {
        if ($value > static::LIMIT_UPPER || $value < static::LIMIT_LOWER) {
            $fullQualifiedNameArray = explode('\\', static::class);

            throw new ValueObjectException('Invalid ' .  array_pop($fullQualifiedNameArray));
        }
    }
}
