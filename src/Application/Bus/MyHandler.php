<?php

declare(strict_types=1);


namespace AML\Application\Bus;


use AML\Infrastructure\Application\Command\CommandSerializer;
use AML\Infrastructure\Queue\QueueService;

class MyHandler implements CommandHandler
{
    /** @var QueueService */
    private $queueService;

    /** @var CommandSerializer */
    private $commandSerializer;

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
            ]
        );
    }
}
