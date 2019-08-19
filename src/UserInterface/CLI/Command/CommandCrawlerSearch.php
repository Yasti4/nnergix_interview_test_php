<?php

namespace AML\UserInterface\CLI\Command;

use AML\Domain\Exception\InvalidSearchDeepException;
use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\Exception\PageAlreadyProcessedException;
use AML\Domain\Exception\SearchUrlNotFoundException;
use AML\Domain\ValueObject\SearchHeader;
use AML\Domain\ValueObject\SearchUrl;
use AML\Domain\ValueObject\SearchUrlCollection;
use AML\Domain\ValueObject\SearchUrlHeaderCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use AML\Application\Service\CrawlerSearchService;
use AML\Application\Service\CrawlerSearchInput;

class CommandCrawlerSearch extends Command
{
    /** @var CrawlerSearchService */
    private $crawlerSearchService;

    public function __construct(CrawlerSearchService $crawlerSearchService)
    {
        parent::__construct();
        $this->crawlerSearchService = $crawlerSearchService;
    }

    protected function configure(): void
    {
        $this
            ->setName('crawler:search')
            ->setDescription('Execute scan web')
            ->addArgument('url', InputArgument::REQUIRED, 'Search url.')
            ->addArgument('deep', InputArgument::OPTIONAL, 'Search deep');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $args = $input->getArguments();

        try {
            $processPage = $this->crawlerSearchService->__invoke(new CrawlerSearchInput(
                (string)$args['url'] ?? '',
                (int)$args['deep'] ?? 0,
                null
            ));

            $output->writeln('Data found:');
            $output->writeln('Url Search: ' . $processPage->page()->url()->value());
            $output->writeln('Reference: ' . $processPage->page()->reference()->toString());


            $this->printHeaders($processPage->page()->headers(), $output);
            $this->printLinks('Internal Links:', $processPage->internalLinks(), $output);
            $this->printLinks('External Links:', $processPage->externalLinks(), $output);

        } catch (InvalidSearchUrlException|InvalidSearchDeepException|SearchUrlNotFoundException $e) {
            var_dump($e->getMessage());
        } catch (PageAlreadyProcessedException $e) {

            $output->writeln($e->getMessage(). json_encode($e->meta(), JSON_PRETTY_PRINT));
        }

        return 0;
    }

    public function printLinks(string $mensage, SearchUrlCollection $internalLinks, OutputInterface $output)
    {
        if ($internalLinks->count()) {
            $output->writeln($mensage);
            /** @var SearchUrl $internalLink */
            foreach ($internalLinks as $internalLink) {
                $output->writeln($internalLink->value());
            }
        }
    }

    public function printHeaders(SearchUrlHeaderCollection $headers, OutputInterface $output)
    {
        if ($headers->count()) {
            $output->writeln('Headers:');
            /** @var SearchHeader $header */
            foreach ($headers as $header) {
                $output->writeln('Header: ' . $header->key() . ' => ' . $header->header());
            }
        }
    }
}
