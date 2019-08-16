<?php

namespace AML\UserInterface\CLI\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use AML\Application\Service\CrawlerSearchService;
use AML\Application\Service\CrawlerSearchInput;

class CommandWorker extends Command
{
    /** @var CommandConsumer */
    private $commandConsumer;
    /** @var CommandBus */
    private $commandBus;
    /** @var LoggerInterface */
    private $logger;

    public function __construct(CommandConsumer $commandConsumer, CommandBus $commandBus, LoggerInterface $logger)
    {
        parent::__construct();

        $this->commandConsumer = $commandConsumer;
        $this->commandBus = $commandBus;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->setName('worker:async-domain-events')
            ->setDescription('Execute scheduled beanstalkd jobs');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            try {
                $command = $this->commandConsumer->consume();

                $this->commandBus->dispatch($command);

                $this->commandConsumer->markAsConsumed();
            } catch (Exception $e) {
                $this->commandConsumer->markAsFailed();
            }

            return 0;
        } catch (\Throwable $e) {
            $this->logger->error('Unknown error while consuming and/or dispatching a command.', [$e]);

            return 1;
        }
    }  return 0;
    }
}
