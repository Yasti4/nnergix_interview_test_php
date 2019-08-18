<?php

declare(strict_types=1);


namespace AML\Infrastructure\Application\Command;

use AML\Application\Bus\Command;
use AML\Application\Bus\CommandExecution as CommandExecutionI;
use AML\Infrastructure\Queue\QueueService;

class CommandExecution implements CommandExecutionI
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

    public function publish(Command $command): void
    {
        $this->queueService->enqueue(
            $this->commandSerializer->serialize($command)
        );
    }
}
