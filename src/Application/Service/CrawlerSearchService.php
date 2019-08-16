<?php

namespace AML\Application\Service;

use AML\Application\Bus\CommandBus;
use AML\Application\Bus\ProcessPageCommand;
use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\Exception\SearchUrlNotFoundException;
use AML\Domain\Repository\InfoUrlRepository;
use AML\Domain\Repository\SearchUrlRepository;
use AML\Domain\Service\ProcessPage;

class CrawlerSearchService
{
    /** @var InfoUrlRepository */
    private $infoUrlRepository;
    /** @var SearchUrlRepository */
    private $searchUrlRepository;
    /** @var CommandBus */
    private $bus;


    public function __construct(
        InfoUrlRepository $infoUrlRepository,
        SearchUrlRepository $searchUrlRepository,
        CommandBus $bus
    )
    {
        $this->searchUrlRepository = $searchUrlRepository;
        $this->infoUrlRepository = $infoUrlRepository;
        $this->bus = $bus;

    }

    /** @throws InvalidSearchUrlException|SearchUrlNotFoundException */
    public function __invoke(CrawlerSearchInput $input): void
    {

        $rootUrl = $input->url();
        $rootDeep = $input->deep();

        $processPage = (new ProcessPage(
            $this->infoUrlRepository,
            $this->searchUrlRepository
        ))->__invoke($rootUrl, $rootDeep);

        $urls = array_merge(
            $processPage->internalLinks()->values(),
            $processPage->externalLinks()->values()
        );

        for ($i = 0; $i < count($urls) && ($rootDeep->value() !== 0); $i++) {
            echo $i . ' de ' . $urls[$i] . PHP_EOL;
            if (!$rootDeep->equals($urls[$i])) {
                $cmd = new ProcessPageCommand($urls[$i]->value(), $rootDeep->value());
                $this->bus->handle($cmd);
            }
        }
    }
}
