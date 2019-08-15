<?php

namespace AML\Domain\ValueObject;

use AML\Domain\Exception\InvalidSearchDeepException;

class SearchDeep
{
    private $deep;

    public function __construct(int $deep)
    {
        if ($deep < 0 || $deep > 10) {
            throw new InvalidSearchDeepException($deep);
        }

        $this->deep = $deep;
    }

    public function value(): int
    {
        return $this->deep;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
