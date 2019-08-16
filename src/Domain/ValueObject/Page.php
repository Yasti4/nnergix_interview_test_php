<?php

namespace AML\Domain\ValueObject;

class Page
{
    public const URL = 'url';
    public const HEADER = 'header';

    private $url;
    private $headers;

    public function __construct(SearchUrl $url, SearchUrlHeaderCollection $headers)
    {
        $this->url = $url;
        $this->headers = $headers;
    }

    public function url(): SearchUrl
    {
        return $this->url;
    }

    public function headers(): SearchUrlHeaderCollection
    {
        return $this->headers;
    }

    public function value(): array
    {
        return [
            self::URL => $this->url,
            self::HEADER => $this->headers
        ];
    }

    public function __toString(): string
    {
        return ''.json_encode($this->value());
    }
}
