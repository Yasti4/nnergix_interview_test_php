<?php

namespace AML\Domain\ValueObject;

class SearchUrlCollection extends Collection
{
    /** @param SearchUrl[] $values */
    public function __construct(array $values = [])
    {
        parent::__construct(array_unique($values));
    }

    protected function type(): string
    {
        return SearchUrl::class;
    }

    /** @return SearchUrl[] */
    public function values(): array
    {
        return $this->values;
    }
}
