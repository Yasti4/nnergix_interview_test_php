<?php

namespace AML\Domain\Repository;

use AML\Domain\ValueObject\PageReference;
use AML\Domain\ValueObject\SearchUrl;
use AML\Domain\ValueObject\SearchDeep;
use AML\Domain\ValueObject\SearchUrlCollection;
use AML\Domain\ValueObject\Page;
use AML\Domain\Exception\SearchUrlNotFoundException;
use AML\Domain\Exception\InvalidSearchUrlException;

interface SearchUrlRepository
{
    /** @throws SearchUrlNotFoundException|InvalidSearchUrlException */
    public function searchInternalsUrl(SearchUrl $url, SearchDeep $deep): SearchUrlCollection;
    
    /** @throws SearchUrlNotFoundException|InvalidSearchUrlException */
    public function searchExternalsUrl(SearchUrl $url, SearchDeep $deep): SearchUrlCollection;

    /** @throws SearchUrlNotFoundException|InvalidSearchUrlException */
    public function findPage(SearchUrl $url, SearchDeep $deep, ?PageReference $pageReference = null): Page;
}
