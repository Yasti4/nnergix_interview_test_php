<?php

declare(strict_types=1);


namespace AML\Application\Command;

use AML\Application\Bus\Command;
use AML\Application\Bus\CommandExecution;
use AML\Application\Bus\CommandHandler;
use AML\Infrastructure\Queue\QueueOption;

class ProcessPageHandler implements CommandHandler
{
    /** @var CommandExecution */
    private $commandExecution;

    public function __construct(CommandExecution $commandExecution)
    {
        $this->commandExecution = $commandExecution;
    }

    /** @param ProcessPageCommand $command */
    public function handle(Command $command, array $options = []): void
    {
        $this->commandExecution->publish(
            $command,
            [QueueOption::QUEUE_NAME()->getKey() => 'nnergix-process-page']
        );
    }
}
