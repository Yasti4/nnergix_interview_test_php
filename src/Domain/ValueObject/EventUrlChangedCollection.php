<?php

namespace AML\Domain\ValueObject;

class EventUrlChangedCollection extends Collection
{
    /** @param EventUrlChanged[] $values */
    public function __construct(array $values = [])
    {
        parent::__construct($values);
    }

    protected function type(): string
    {
        return EventUrlChanged::class;
    }

    /** @return EventUrlChanged[] */
    public function values(): array
    {
        return $this->values;
    }

}
