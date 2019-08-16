<?php

namespace AML\Domain\Repository;

use AML\Domain\ValueObject\SearchUrl;
use AML\Domain\ValueObject\SearchUrlCollection;
use AML\Domain\ValueObject\SearchUrlHeaderCollection;
use AML\Domain\ValueObject\Page;

interface InfoUrlRepository
{
    public function persist(Page $page): void;
    
    public function findUrl(SearchUrl $url): ?Page;
}
