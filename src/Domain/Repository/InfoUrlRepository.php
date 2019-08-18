<?php

namespace AML\Domain\Repository;

use AML\Domain\ValueObject\Page;
use AML\Domain\ValueObject\SearchUrl;

interface InfoUrlRepository
{
    public function persist(Page $page): void;
    
    public function findUrl(SearchUrl $url): ?Page;
}
