<?php

namespace AML\UserInterface\CLI\Command;

use AML\Application\Command\ProcessPageCommand;
use AML\Application\Service\CrawlerSearchInput;
use AML\Application\Service\CrawlerSearchService;
use AML\Infrastructure\Application\Command\CommandConsumer;
use AML\Infrastructure\Queue\QueueOption;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandWorkerProcessPage extends Command
{
    /** @var CommandConsumer */
    private $commandConsumer;
    /** @var LoggerInterface */
    private $logger;
    /** @var CrawlerSearchService */
    private $crawlerSearchService;

    public function __construct(
        CommandConsumer $commandConsumer,
        CrawlerSearchService $crawlerSearchService,
        LoggerInterface $logger
    )
    {
        parent::__construct();

        $this->commandConsumer = $commandConsumer;
        $this->logger = $logger;
        $this->crawlerSearchService = $crawlerSearchService;
    }

    protected function configure(): void
    {
        $this
            ->setName('worker:async-process-page')
            ->setDescription('Execute scheduled beanstalkd jobs');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            try {
                /** @var ProcessPageCommand $command */
                $command = $this->commandConsumer->consume(
                    [QueueOption::QUEUE_NAME()->getKey() => 'nnergix-process-page']
                );

                $processPage = $this->crawlerSearchService->__invoke(new CrawlerSearchInput(
                    $command->url()->value() ?? '',
                    $command->deep()->value() ?? -1
                ));

//                $this->commandBus->handle($command);

                $this->commandConsumer->markAsConsumed();
            } catch (\Exception $e) {
                var_dump($e->getMessage());
                $this->commandConsumer->markAsFailed();
            }

            return 0;
        } catch (\Throwable $e) {
            $this->logger->error('Unknown error while consuming and/or dispatching a command.', [$e->getMessage()]);

            return 1;
        }
    }
}
