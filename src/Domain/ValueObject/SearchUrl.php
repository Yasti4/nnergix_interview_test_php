<?php

namespace AML\Domain\ValueObject;

use AML\Domain\Exception\InvalidSearchUrlException;

class SearchUrl
{
    private $url;

    public function __construct(string $url)
    {
        $url = trim($url);

        if (!$this->isHttpOrHttps($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidSearchUrlException($url);
        }

        $this->url = $url;
    }

    private function isHttpOrHttps(string $url): bool
    {
        return (strpos($url, "http://") === 0 || strpos($url, "https://") === 0);
    }

    public function contains(SearchUrl $other): bool
    {
        return !empty(preg_match(sprintf('*(%s)*', $this->value()), $other->value()));
    }

    public function value(): string
    {
        return $this->url;
    }

    public function getUrl(): string //TODO:  Don't remove key "get", otherwise it is serialized empty
    {
        return $this->url;
    }


    public function equals(SearchUrl $other): bool
    {
        return $this->value() === $other->value();
    }
    
    public function __toString(): string
    {
        return $this->value();
    }
}
