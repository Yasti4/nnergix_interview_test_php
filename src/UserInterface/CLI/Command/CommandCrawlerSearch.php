<?php

namespace AML\UserInterface\CLI\Command;

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

        $this->crawlerSearchService->__invoke(new CrawlerSearchInput(
            (string)$args['url'] ?? '',
            (int)$args['deep'] ?? -1
        ));

        return 0;
    }
}
