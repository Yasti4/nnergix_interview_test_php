<?php

namespace AML\Domain\Repository;

use AML\Domain\ValueObject\SearchUrl;
use AML\Domain\ValueObject\SearchUrlCollection;
use AML\Domain\ValueObject\SeachUrlHeaderCollection;
use AML\Domain\ValueObject\Page;

interface InfoUrlRepository
{
    public function persist(SearchUrl $url, SeachUrlHeaderCollection $headers): SearchUrlCollection;
    
    public function findUrl(SearchUrl $url): ?Page;
}
