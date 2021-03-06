<?php

declare(strict_types=1);


namespace AML\Application\Bus;


interface CommandHandler
{
    public function handle(Command $command, array $options = []): void;
}
