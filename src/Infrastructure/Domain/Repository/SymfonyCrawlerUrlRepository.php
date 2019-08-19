<?php

namespace AML\Infrastructure\Domain\Repository;

use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\Exception\SearchUrlNotFoundException;
use AML\Domain\Repository\SearchUrlRepository;
use AML\Domain\ValueObject\Page;
use AML\Domain\ValueObject\PageReference;
use AML\Domain\ValueObject\SearchDeep;
use AML\Domain\ValueObject\SearchHeader;
use AML\Domain\ValueObject\SearchUrl;
use AML\Domain\ValueObject\SearchUrlCollection;
use AML\Domain\ValueObject\SearchUrlHeaderCollection;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SymfonyCrawlerUrlRepository implements SearchUrlRepository
{
    private const SEARCH_INTERNAL = 'internal';
    private const SEARCH_EXTERNAL = 'external';

    private const HEADER_ALLOWED = [
        'date',
        'expires',
        'content-type',
        SearchHeader::LAST_MODIFIED,
        'content-encoding'
    ];

    /** @var HttpClientInterface */
    private $httpClient;
    /** @var null|Crawler */
    private $crawler;
    /** @var null|ResponseInterface */
    private $responseClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->crawler = null;
        $this->responseClient = null;
    }

    /** @throws SearchUrlNotFoundException|InvalidSearchUrlException */
    public function searchInternalsUrl(SearchUrl $url, SearchDeep $deep): SearchUrlCollection
    {
        return $this->searchUrls($url, self::SEARCH_INTERNAL);
    }

    public function findPage(SearchUrl $url, SearchDeep $deep, ?PageReference $pageReference = null): Page
    {
        if (is_null($this->responseClient)) {
            $this->getCrawler($url);
        }
        return new Page($url, $this->getHeaders(), $pageReference);
    }

    /** @throws SearchUrlNotFoundException|InvalidSearchUrlException */
    public function searchExternalsUrl(SearchUrl $url, SearchDeep $deep): SearchUrlCollection
    {
        return $this->searchUrls($url, self::SEARCH_EXTERNAL);
    }

    /** @throws SearchUrlNotFoundException|InvalidSearchUrlException */
    private function searchUrls(SearchUrl $url, string $type): SearchUrlCollection
    {
        $crawler = $this->getCrawler($url);
        /** @var SearchUrl[] $urls */
        $urls = [];

        $crawler->filter('a')->each(function (Crawler $node, $i) use ($url, &$urls, $type) {
            try {
                $uri = new SearchUrl($node->link()->getUri());
                if ($type === self::SEARCH_INTERNAL && $url->contains($uri)) {
                    $urls[] = $uri;
                } elseif ($type === self::SEARCH_EXTERNAL && !$url->contains($uri)) {
                    $urls[] = $uri;
                }
            } catch (InvalidSearchUrlException $e) {
            }
        });

        return new SearchUrlCollection($urls);
    }

    private function getHeaders(): SearchUrlHeaderCollection
    {
        $headers = $this->getResponseHeaders();
        $metas = [];
        foreach (self::HEADER_ALLOWED as $header) {
            if (array_key_exists($header, $headers)) {
                $metas[] = new SearchHeader($header, $headers[$header][0]);
            }
        }
        $metas[] = new SearchHeader('status', (string)$this->getResponseStatusCode());
        return new SearchUrlHeaderCollection($metas);

    }

    /** @throws SearchUrlNotFoundException|InvalidSearchUrlException */
    private function getCrawler(SearchUrl $url): Crawler
    {
        if ($this->crawler) {
            $uri = new SearchUrl($this->crawler->getUri());

            if ($url->equals($uri)) {
                return $this->crawler;
            }
        }

        try {
            $response = $this->httpClient->request('GET', $url->value());
            $this->responseClient = $response;
            if ($response->getStatusCode() !== 200) {
                throw new SearchUrlNotFoundException($url->value());
            }
        } catch (\Throwable $th) {
            throw new SearchUrlNotFoundException($url->value());
        }


        return new Crawler($response->getContent(), $url->value());
    }

    private function getResponseHeaders(): array
    {
        try {
            return $this->responseClient ? $this->responseClient->getHeaders() : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function getResponseStatusCode(): int
    {
        try {
            return $this->responseClient ? $this->responseClient->getStatusCode() : 0;
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
