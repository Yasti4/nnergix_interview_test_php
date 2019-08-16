<?php

namespace AML\Domain\Service;

use AML\Domain\Repository\InfoUrlRepository;
use AML\Domain\Repository\SearchUrlRepository;
use AML\Domain\ValueObject\Page;
use AML\Domain\ValueObject\SearchUrl;
use AML\Domain\ValueObject\SearchDeep;

class PageFinder
{
    /** @var InfoUrlRepository */
    private $infoUrlRepository;
    /** @var SearchUrlRepository */
    private $searchUrlRepository;

    public function __construct(
        InfoUrlRepository $infoUrlRepository,
        SearchUrlRepository $searchUrlRepository
    ) {
        $this->infoUrlRepository = $infoUrlRepository;
        $this->searchUrlRepository = $searchUrlRepository;
    }

    public function __invoke(SearchUrl $searchUrl, SearchDeep $deep): Page
    {
        $page = $this->infoUrlRepository->findUrl($searchUrl);
        if (is_null($page)) {

            $page = $this->searchUrlRepository->findPage($searchUrl, $deep);
            $this->infoUrlRepository->persist($page);
        }

        return $page;
    }
}
