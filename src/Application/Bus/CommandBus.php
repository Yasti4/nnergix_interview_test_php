<?php

namespace AML\Application\Bus;

interface CommandBus
{
    public function handle(Command $command): void;
}
