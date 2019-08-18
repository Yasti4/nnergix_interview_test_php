<?php

namespace AML\Domain\Service;

use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\Exception\SearchUrlNotFoundException;
use AML\Domain\Repository\InfoUrlRepository;
use AML\Domain\Repository\SearchUrlRepository;
use AML\Domain\ValueObject\Page;
use AML\Domain\ValueObject\PageProcessed;
use AML\Domain\ValueObject\SearchDeep;
use AML\Domain\ValueObject\SearchHeader;
use AML\Domain\ValueObject\SearchUrl;
use AML\Domain\ValueObject\SearchUrlCollection;

class ProcessPage
{
    /** @var InfoUrlRepository */
    private $infoUrlRepository;
    /** @var SearchUrlRepository */
    private $searchUrlRepository;
    /** @var PageFinder */
    private $pageFinder;

    public function __construct(
        InfoUrlRepository $infoUrlRepository,
        SearchUrlRepository $searchUrlRepository
    )
    {
        $this->infoUrlRepository = $infoUrlRepository;
        $this->searchUrlRepository = $searchUrlRepository;
        $this->pageFinder = new PageFinder($infoUrlRepository, $searchUrlRepository);
    }

    /** @throws InvalidSearchUrlException|SearchUrlNotFoundException */
    public function __invoke(SearchUrl $searchUrl, SearchDeep $deep): PageProcessed
    {
        $page = $this->pageFinder->__invoke($searchUrl, $deep);

        /** @var SearchUrlCollection $internalLinks */
        $internalLinks = $this->searchUrlRepository->searchInternalsUrl($page->url(), $deep);
        /** @var SearchUrlCollection $externalLinks */
        $externalLinks = $this->searchUrlRepository->searchExternalsUrl($page->url(), $deep);
        return new PageProcessed($page, $internalLinks, $externalLinks);
    }

}
