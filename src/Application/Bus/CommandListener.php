<?php

declare(strict_types=1);


namespace AML\Application\Listener;


use AML\Application\Bus\Command;
use AML\Application\Bus\ProcessPageCommand;

class CommandListener implements ListenerHandler
{
    /** @param Command $command */
    public function handle($command): void
    {
        if ($command instanceof ProcessPageCommand) {
            // servicio que eejcutara: (new Z())->A();
        }


    }
}
