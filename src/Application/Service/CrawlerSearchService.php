<?php

namespace AML\Application\Service;

use Psr\Log\LoggerInterface;
use AML\Domain\Repository\SearchUrlRepository;
use AML\Domain\Repository\InfoUrlRepository;
use AML\Domain\Service\PageFinder;

class CrawlerSearchService
{
    // /** @var InfoUrlRepository */
    // private $infoUrlRepository;
    /** @var SearchUrlRepository */
    private $searchUrlRepository;
    // TODO: Poner los que faltan!
    /** @var PageFinder */
    private $pageFinder;
    /** @var InfoUrlRepository */
    private $infoUrlRepository;

    public function __construct(
        InfoUrlRepository $infoUrlRepository,
        SearchUrlRepository $searchUrlRepository
        ) {
        $this->searchUrlRepository = $searchUrlRepository;
        $this->infoUrlRepository = $infoUrlRepository;
        $this->pageFinder = new PageFinder($infoUrlRepository, $searchUrlRepository);
    }

    public function __invoke(CrawlerSearchInput $input): void
    {
        $rootUrl = $input->url();
        $rootDeep = $input->deep();
        $page = $this->pageFinder->__invoke($rootUrl, $rootDeep);




        $urls = array_merge(
            $this->searchUrlRepository->searchInternalsUrl($rootUrl, $input->deep())->values(),
            $this->searchUrlRepository->searchExternalsUrl($rootUrl, $input->deep())->values()
        );

        foreach ($urls as $url) {
            if (!$rootUrl->equals($url)) {
                echo $url->__toString().PHP_EOL;
            }
        }
    }
}
