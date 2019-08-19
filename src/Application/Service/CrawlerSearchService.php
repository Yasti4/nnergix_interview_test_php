<?php

namespace AML\Application\Service;

use AML\Application\Bus\CommandBus;
use AML\Application\Command\ProcessPageCommand;
use AML\Domain\Exception\InvalidSearchDeepException;
use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\Exception\SearchUrlNotFoundException;
use AML\Domain\Repository\InfoUrlRepository;
use AML\Domain\Repository\SearchUrlRepository;
use AML\Domain\Service\ProcessPage;
use AML\Domain\ValueObject\PageProcessed;

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

    /**
     * @throws InvalidSearchUrlException
     * @throws InvalidSearchDeepException
     * @throws SearchUrlNotFoundException
     */
    public function __invoke(CrawlerSearchInput $input): PageProcessed
    {

        $rootUrl = $input->url();
        $rootDeep = $input->deep();
        $pageReference = $input->pageReference();

        $processPage = (new ProcessPage(
            $this->infoUrlRepository,
            $this->searchUrlRepository
        ))->__invoke($rootUrl, $rootDeep, $pageReference);

        $urls = array_merge(
            $processPage->internalLinks()->values(),
            $processPage->externalLinks()->values()
        );


        for ($i = 0; $i < count($urls) && ($rootDeep->value() > 0); $i++) {
            if (!$rootUrl->equals($urls[$i])) {
//                echo $urls[$i]->value().PHP_EOL;
                $cmd = new ProcessPageCommand(
                    $urls[$i]->value(),
                    $rootDeep->value() - 1,
                    $processPage->page()->reference()->toString()
                );
                $this->bus->handle($cmd);
            }
        }
        return $processPage;
    }
}
