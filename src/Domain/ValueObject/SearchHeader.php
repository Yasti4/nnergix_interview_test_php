<?php

namespace AML\Domain\ValueObject;

class SearchHeader
{
    public const KEY = 'key';
    public const HEADER = 'header';

    public const LAST_MODIFIED = 'last-modified';

    private $key;
    private $header;

    public function __construct(string $key, string $header)
    {
        $this->key = $key;
        $this->header = $header;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function header(): string
    {
        return $this->header;
    }

    public function value(): array
    {
        return [
            self::KEY => $this->key,
            self::HEADER => $this->header
        ];
    }

    public function equalsHeader(SearchHeader $otherHeader): bool
    {
        return $this->header() === $otherHeader->header();
    }

    public function __toString(): string
    {
        return $this->key();
    }
}
