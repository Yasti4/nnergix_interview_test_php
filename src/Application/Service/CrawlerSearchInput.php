<?php

namespace AML\Application\Service;

use AML\Domain\Exception\InvalidSearchDeepException;
use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\ValueObject\PageReference;
use AML\Domain\ValueObject\SearchDeep;
use AML\Domain\ValueObject\SearchUrl;

class CrawlerSearchInput
{
    private $url;
    private $deep;
    private $pageReference;

    /** @throws InvalidSearchUrlException|InvalidSearchDeepException */
    public function __construct(string $url, int $deep, ?string $pageReference = null)
    {
        $this->url = new SearchUrl($url);
        $this->deep = new SearchDeep($deep);
        $this->pageReference = $pageReference ? PageReference::fromString($pageReference) : $pageReference;
    }

    public function url(): SearchUrl
    {
        return $this->url;
    }

    public function deep(): SearchDeep
    {
        return $this->deep;
    }

    public function pageReference(): ?PageReference
    {
        return $this->pageReference;
    }

}
