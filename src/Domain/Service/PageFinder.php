<?php

namespace AML\Domain\Service;

use AML\Domain\Repository\InfoUrlRepository;
use AML\Domain\Repository\SearchUrlRepository;
use AML\Domain\ValueObject\Page;
use AML\Domain\ValueObject\SearchDeep;
use AML\Domain\ValueObject\SearchHeader;
use AML\Domain\ValueObject\SearchUrl;

class PageFinder
{
    /** @var InfoUrlRepository */
    private $infoUrlRepository;
    /** @var SearchUrlRepository */
    private $searchUrlRepository;

    public function __construct(
        InfoUrlRepository $infoUrlRepository,
        SearchUrlRepository $searchUrlRepository
    )
    {
        $this->infoUrlRepository = $infoUrlRepository;
        $this->searchUrlRepository = $searchUrlRepository;
    }

    public function __invoke(SearchUrl $searchUrl, SearchDeep $deep): Page
    {
        $page = $this->infoUrlRepository->findUrl($searchUrl);

        if (!is_null($page)) {
            $tempPage = $this->searchUrlRepository->findPage($searchUrl, $deep);

            $isChangePage = $this->checkHeaderIfPageChanged($page, $tempPage);

            if ($isChangePage) {

                /*
                 * TODO: Evento de dominio informando del cambio
                 */

                $page = $tempPage;
                $this->infoUrlRepository->persist($tempPage);
            }

        } else if (is_null($page)) {

            $page = $this->searchUrlRepository->findPage($searchUrl, $deep);

            $this->infoUrlRepository->persist($page);
        }

        return $page;
    }

    private function checkHeaderIfPageChanged(Page $oldPage, Page $newPage): bool
    {
        /** @var SearchHeader[] $headers */
        $oldPageheaderLastModified = $this->getHeaderBy(
            $oldPage->headers()->values(), SearchHeader::LAST_MODIFIED);
        $newPageheaderLastModified = $this->getHeaderBy(
            $newPage->headers()->values(), SearchHeader::LAST_MODIFIED);

        return !$newPageheaderLastModified->equalsHeader($oldPageheaderLastModified);
    }

    /**
     * @param SearchHeader[] $headers
     */
    private function getHeaderBy(array $headers, string $keyHeader): SearchHeader
    {
        foreach ($headers as $header) {
            if ($header->key() === $keyHeader) {
                return $header;
            }
        }

        return new SearchHeader('', '');
    }
}
