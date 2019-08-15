<?php

namespace AML\Infrastructure\Domain\Repository;

use AML\Domain\Repository\SearchUrlRepository;
use AML\Domain\ValueObject\SearchUrl;
use AML\Domain\ValueObject\SearchDeep;
use AML\Domain\ValueObject\Page;
use AML\Domain\ValueObject\SearchUrlCollection;
use AML\Domain\Exception\SearchUrlNotFoundException;
use AML\Domain\Exception\InvalidSearchUrlException;
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
        'status',

    ];

    /** @var HttpClientInterface */
    private $httpClient;
    /** @var Crawler */
    private $crawler;

    /** @var ResponseInterface */
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

    public function findPage(SearchUrl $url, SearchDeep $deep): Page
    {
        if (is_null($this->responseClient)) {
            $this->getCrawler($url, $deep);
        }

        // $page = new Page($url, new AML\Domain\ValueObject\SeachUrlHeaderCollection(
        //     AML\Domain\ValueObject\SearchHeader()
        // ));
        die(var_dump($this->responseClient->getInfo()));
    }

    /** @throws SearchUrlNotFoundException|InvalidSearchUrlException */
    public function searchExternalsUrl(SearchUrl $url, SearchDeep $deep): SearchUrlCollection
    {
        return $this->searchUrls($url, self::SEARCH_EXTERNAL);
    }

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

    /** @throws SearchUrlNotFoundException */
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
        } catch (\Throwable $th) {
            throw new SearchUrlNotFoundException($url->value());
        }

        if ($response->getStatusCode() !== 200) {
            throw new SearchUrlNotFoundException($url->value());
        }

        return new Crawler($response->getContent(), $url->value());
    }
}


// die(var_dump($response->getHeaders()));
        // $metas = $crawler->filter('head meta');
