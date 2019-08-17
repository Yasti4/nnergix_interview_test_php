<?php

declare(strict_types=1);

namespace AML\Infrastructure\Application\Command;

use AML\Application\Bus\{Command, CommandBus};
use SimpleBus\Message\Bus\MessageBus;

class SimpleBusSyncCommandBus implements CommandBus
{
    /** @var MessageBus */
    private $bus;

    public function __construct(MessageBus $bus)
    {
        $this->bus = $bus;
    }

    public function handle(Command $command): void
    {
        $this->handle($command);
    }
}
