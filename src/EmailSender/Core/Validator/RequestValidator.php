<?php

namespace EmailSender\Core\Validator;

use InvalidArgumentException;

/**
 * Class RequestValidator
 *
 * @package EmailSender\Core
 */
abstract class RequestValidator
{
    /**
     * @var array
     */
    protected $requiredProperties = [];

    /**
     * @var array
     */
    protected $optionalProperties = [];

    /**
     * Validate the request.
     *
     * @param $request
     *
     * @throws \InvalidArgumentException
     */
    public function validate($request): void
    {
        if (!is_array($request)) {
            throw new InvalidArgumentException('Empty or invalid request.');
        }

        foreach ($request as $propertyName => $property) {
            if (!in_array($propertyName, array_merge($this->requiredProperties, $this->optionalProperties), true)) {
                throw new InvalidArgumentException('Not allowed property: ' . $propertyName);
            }
        }

        foreach ($this->requiredProperties as $propertyName) {
            if (!array_key_exists($propertyName, $request)) {
                throw new InvalidArgumentException('Missing required property: ' . $propertyName);
            }
        }
    }
}
