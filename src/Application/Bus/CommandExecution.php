<?php

declare(strict_types=1);


namespace AML\Application\Bus;


interface CommandExecution
{
    public function publish(Command $command, array $options = []): void;
}
