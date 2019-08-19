<?php

namespace AML\UserInterface\CLI\Command;

use AML\Application\Service\InfoProcessPageInput;
use AML\Application\Service\InfoProcessPageService;
use AML\Domain\ValueObject\SearchHeader;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandProcessPageQuery extends Command
{
    /** @var LoggerInterface */
    private $logger;
    /** @var InfoProcessPageService */
    private $infoProcessPageService;

    public function __construct(InfoProcessPageService $infoProcessPageService, LoggerInterface $logger)
    {
        parent::__construct();

        $this->infoProcessPageService = $infoProcessPageService;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->setName('worker:domain-events-url-changed-query')
            ->setDescription('Execute scheduled beanstalkd jobs')
            ->addArgument('reference', InputArgument::REQUIRED, 'Uuid reference');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $reference = $input->getArgument('reference');
        if (!is_string($reference)) {
            throw new \InvalidArgumentException('Internal error, the input reference is not string');
        }

        $pages = $this->infoProcessPageService->__invoke(
            new InfoProcessPageInput($reference)
        );

        if (empty($pages)) {
            $output->writeln('No information available. Try another time');
            return 1;
        }
        $output->writeln('Data found:');
        foreach ($pages as $page) {
            $output->writeln($page->url()->value());
            $output->writeln('Headers:');
            $headers = json_encode(array_map(function ($header) {
                /** @var SearchHeader $header */
                return $header->value();
            }, $page->headers()->values()), JSON_PRETTY_PRINT);
            if (is_string($headers)) {
                $output->writeln($headers);
            }
        }
        return 0;
    }
}
