<?php

namespace AML\UserInterface\CLI\Command;

use AML\Application\Service\InfoUrlChangedInput;
use AML\Application\Service\InfoUrlChangedService;
use AML\Domain\ValueObject\EventUrlChanged;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandDomainEventInfoUrlChangedQuery extends Command
{

    /** @var LoggerInterface */
    private $logger;
    /** @var InfoUrlChangedService */
    private $infoUrlChangedService;

    public function __construct(InfoUrlChangedService $infoUrlChangedService, LoggerInterface $logger)
    {
        parent::__construct();

        $this->infoUrlChangedService = $infoUrlChangedService;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->setName('worker:domain-events-url-changed-query')
            ->setDescription('Execute scheduled beanstalkd jobs');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $urlsChanged = $this->infoUrlChangedService->__invoke(
            new InfoUrlChangedInput()
        );
        if (empty($urlsChanged->count())) {
            $output->writeln('No information available. Try another time');
            return 1;
        }
        $output->writeln('Data found:');
        foreach ($urlsChanged as $urlChanged) {
            /** @var EventUrlChanged $urlChanged */
            $output->writeln("[{$urlChanged->occurredOn()}] ".$urlChanged->url()->value());
        }

        return 0;
    }
}
