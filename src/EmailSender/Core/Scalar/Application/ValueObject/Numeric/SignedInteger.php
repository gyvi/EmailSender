<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\Numeric;

use EmailSender\Core\Scalar\Application\Contract\ValueObjectInterface;
use EmailSender\Core\Scalar\Application\Exception\ValueObjectException;
use JsonSerializable;

/**
 * Class SignedInteger
 *
 * @package EmailSender\Core\Scalar
 */
class SignedInteger implements ValueObjectInterface, JsonSerializable
{
    /**
     * Lower limit of the SignedInteger.
     */
    const LIMIT_LOWER = PHP_INT_MIN;

    /**
     * Upper limit of the SignedInteger.
     */
    const LIMIT_UPPER = PHP_INT_MAX;

    /**
     * @var int
     */
    private $value;

    /**
     * SignedInteger constructor.
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

    /**
     * @return int
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }
}
