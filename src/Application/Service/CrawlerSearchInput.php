<?php

namespace AML\Application\Service;

use AML\Domain\ValueObject\SearchUrl;
use AML\Domain\ValueObject\SearchDeep;

class CrawlerSearchInput
{
    private $url;
    private $deep;

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
}