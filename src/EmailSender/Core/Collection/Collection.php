<?php

namespace EmailSender\Core\Collection;

use IteratorAggregate;
use Countable;
use JsonSerializable;
use InvalidArgumentException;
use ArrayIterator;

/**
 * Class Collection
 *
 * @package EmailSender\Core
 */
abstract class Collection implements IteratorAggregate, Countable, JsonSerializable
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var string
     */
    private $type;

    /**
     * Collection constructor.
     */
    public function __construct()
    {
        $this->items = array();
        $this->type  = $this->getType();
    }

    /**
     * Defines the type of instances.
     *
     * @return string
     */
    abstract protected function getType(): string;

    /**
     * Add an instance to the collection.
     *
     * @param object $item
     *
     * @throws \InvalidArgumentException
     */
    public function add($item): void
    {
        if (!$item instanceof $this->type) {
            throw new InvalidArgumentException(
                'Invalid object added: ' . gettype($item) . ', expected: ' . $this->getType()
            );
        }

        $this->items[] = $item;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
