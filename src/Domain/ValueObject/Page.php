<?php

namespace AML\Domain\ValueObject;

class Page
{
    public const URL = 'url';
    public const HEADER = 'header';

    private $url;

    public function __construct(SearchUrl $url, SeachUrlHeaderCollection $header)
    {
        $this->url = $url;
        $this->header = $header;
    }

    public function url(): SearchUrl
    {
        return $this->url;
    }

    public function header(): SeachUrlHeaderCollection
    {
        return $this->header;
    }

    public function value(): array
    {
        return [
            self::URL => $this->url,
            self::HEADER => $this->header
        ];
    }

    public function __toString(): int
    {
        return $this->value();
    }
}
