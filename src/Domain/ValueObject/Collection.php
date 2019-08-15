<?php

namespace AML\Domain\ValueObject;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

abstract class Collection implements Countable, IteratorAggregate
{
    /** @var array */
    protected $values;

    public function __construct(array $values = [])
    {
        $this->allIsInstanceOf($values);

        $this->values = $values;
    }

    abstract protected function type(): string;

    private function allIsInstanceOf(array $values): void
    {
        $class = $this->type();

        foreach ($values as $value) {
            if (!($value instanceof $class)) {
                throw new \InvalidArgumentException();
            }
        }
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    public function count(): int
    {
        return count($this->values);
    }

    public function values(): array
    {
        return $this->values;
    }
}
