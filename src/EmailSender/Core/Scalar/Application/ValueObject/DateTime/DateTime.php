<?php

namespace EmailSender\Core\Scalar\Application\ValueObject\DateTime;

use EmailSender\Core\Scalar\Application\Contract\ValueObjectInterface;

/**
 * Class DateTime
 *
 * @package EmailSender\Core\Scalar
 */
class DateTime implements ValueObjectInterface
{
    /**
     * @return string
     */
    public function getValue(): string
    {
        /** TODO: create proper DDD DateTime implementation */
        return (new \DateTime('now'))->format('Y:m:d H:i:s');
    }
}
