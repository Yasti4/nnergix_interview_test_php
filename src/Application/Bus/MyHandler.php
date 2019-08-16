<?php

declare(strict_types=1);


namespace AML\Application\Bus;


class MyHandler implements CommandHandler
{
    /** @var QueueService */
    private $queueService;

    public function __construct(QueueService $queueService, CommandSerializer $commandSerializer)
    {
        $this->queueService = $queueService;
        $this->commandSerializer = $commandSerializer;
    }

    /** @param ProcessPageCommand $command */
    public function handle(Command $command): void
    {
        $this->queueService->enqueue(
            $this->commandSerializer->serialize($command),
            [
                QueueOption::SECONDS_TO_DELAY()->getKey() => 0
            ]
        );
    }
}
