<?php

namespace AML\Domain\Service;

use AML\Domain\Event\DomainEventPublisher;
use AML\Domain\Event\SearchUrlChangedCreated;
use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\Exception\PageAlreadyProcessedException;
use AML\Domain\Exception\SearchUrlNotFoundException;
use AML\Domain\Repository\InfoUrlRepository;
use AML\Domain\Repository\SearchUrlRepository;
use AML\Domain\ValueObject\Page;
use AML\Domain\ValueObject\PageReference;
use AML\Domain\ValueObject\SearchDeep;
use AML\Domain\ValueObject\SearchHeader;
use AML\Domain\ValueObject\SearchUrl;
use Doctrine\ORM\ORMException;

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

    /** @throws InvalidSearchUrlException|SearchUrlNotFoundException|PageAlreadyProcessedException
     * @throws ORMException
     */
    public function __invoke(SearchUrl $searchUrl, SearchDeep $deep, ?PageReference $pageReference = null): Page
    {
        $page = $this->infoUrlRepository->findUrl($searchUrl);

        if (!is_null($page)) {
            $tempPage = $this->searchUrlRepository->findPage($searchUrl, $deep, $pageReference);

            $isChangePage = $this->checkHeaderIfPageChanged($page, $tempPage);

            if ($isChangePage) {

                DomainEventPublisher::instance()->publish(new SearchUrlChangedCreated($tempPage->url()->value()));

                $page = $tempPage;
                $this->infoUrlRepository->update($tempPage);
            } else if (!$isChangePage) {
                throw new PageAlreadyProcessedException($tempPage->url()->value());
            }

        } else if (is_null($page)) {
            $page = $this->searchUrlRepository->findPage($searchUrl, $deep, $pageReference);

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

        if (is_null($oldPageheaderLastModified) || is_null($newPageheaderLastModified)) {
            return true;
        }

        return !$newPageheaderLastModified->equalsHeader($oldPageheaderLastModified);
    }

    /**
     * @param SearchHeader[] $headers
     */
    private function getHeaderBy(array $headers, string $keyHeader): ?SearchHeader
    {
        foreach ($headers as $header) {
            if ($header->key() === $keyHeader) {
                return $header;
            }
        }

        return null;
    }
}
