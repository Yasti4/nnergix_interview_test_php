<?php

namespace AML\Domain\Repository;

use AML\Domain\ValueObject\SearchUrl;
use AML\Domain\ValueObject\SearchUrlCollection;
use AML\Domain\ValueObject\SearchUrlHeaderCollection;
use AML\Domain\ValueObject\Page;

interface InfoUrlChangeRepository
{
    public function persist(array $urlChange): void; // TODO: Change
    
    public function getAll(): array ; // TODO: Change
}
