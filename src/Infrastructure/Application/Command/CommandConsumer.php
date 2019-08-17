<?php

declare(strict_types=1);

namespace AML\Infrastructure\Application\Command;

use AML\Application\Bus\Command;
use AML\Infrastructure\Queue\QueueService;

class CommandConsumer
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

    public function consume(): Command
    {
        return $this->commandSerializer->deserialize(
            $this->queueService->dequeue()
        );
    }
}
