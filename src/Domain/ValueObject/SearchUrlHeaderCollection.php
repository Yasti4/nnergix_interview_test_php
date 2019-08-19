<?php

namespace AML\Domain\ValueObject;

class SearchUrlHeaderCollection extends Collection
{
    /** @param SearchHeader[] $values */
    public function __construct(array $values = [])
    {
        parent::__construct(array_unique($values));
    }

    protected function type(): string
    {
        return SearchHeader::class;
    }

    /** @return SearchHeader[] */
    public function values(): array
    {
        return $this->values;
    }
}
