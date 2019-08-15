<?php

namespace AML\Infrastructure\Domain\Repository;

use AML\Domain\Repository\InfoUrlRepository;
use AML\Domain\ValueObject\SearchUrl;
use AML\Domain\ValueObject\SearchUrlCollection;
use AML\Domain\ValueObject\SeachUrlHeaderCollection;
use AML\Domain\ValueObject\Page;

class PostgressInfoUrlRepository implements InfoUrlRepository
{
    public function __construct()
    {
    }

    public function persist(SearchUrl $url, SeachUrlHeaderCollection $headers): SearchUrlCollection
    {
    }

    public function findUrl(SearchUrl $url): ?Page
    {
        return null;
    }
}
