<?php

namespace AML\Domain\Repository;

use AML\Domain\ValueObject\Page;
use AML\Domain\ValueObject\PageReference;
use AML\Domain\ValueObject\SearchUrl;
use Doctrine\ORM\ORMException;

interface InfoUrlRepository
{
    public function persist(Page $page): void;

    /** @throws ORMException */
    public function update(Page $page): void;

    public function findUrl(SearchUrl $url): ?Page;

    /** @return Page[] */
    public function findByReference(PageReference $pageReference): array;
}
