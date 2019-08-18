<?php

namespace AML\Application\Command;

use AML\Application\Bus\Command;
use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\Exception\InvalidSearchDeepException;
use AML\Domain\ValueObject\SearchDeep;
use AML\Domain\ValueObject\SearchUrl;

class ProcessPageCommand implements Command
{
    private $url;
    private $deep;

    /** @throws InvalidSearchUrlException|InvalidSearchDeepException */
    public function __construct(string $url, int $deep)
    {
        $this->url = new SearchUrl($url);
        $this->deep = new SearchDeep($deep);
    }

    public function url(): SearchUrl
    {
        return $this->url;
    }

    public function deep(): SearchDeep
    {
        return $this->deep;
    }

    public function getUrl(): string
    {
        return $this->url->value();
    }

    public function getDeep(): int
    {
        return $this->deep->value();
    }
}
