<?php

namespace AML\Application\Command;

use AML\Application\Bus\Command;
use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\Exception\InvalidSearchDeepException;
use AML\Domain\ValueObject\Id;
use AML\Domain\ValueObject\PageReference;
use AML\Domain\ValueObject\SearchDeep;
use AML\Domain\ValueObject\SearchUrl;

class ProcessPageCommand implements Command
{
    private $url;
    private $deep;
    private $pageReference;

    /** @throws InvalidSearchUrlException|InvalidSearchDeepException */
    public function __construct(string $url, int $deep, string $pageReference)
    {
        $this->url = new SearchUrl($url);
        $this->deep = new SearchDeep($deep);
        $this->pageReference =  PageReference::fromString($pageReference);
    }

    public function url(): SearchUrl
    {
        return $this->url;
    }

    public function deep(): SearchDeep
    {
        return $this->deep;
    }

    public function pageReference(): PageReference
    {
        return $this->pageReference;
    }

    public function getUrl(): string
    {
        return $this->url->value();
    }

    public function getDeep(): int
    {
        return $this->deep->value();
    }

    public function getPageReference(): string
    {
        return $this->pageReference->toString();
    }
}
