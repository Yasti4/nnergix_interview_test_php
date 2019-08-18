<?php

declare(strict_types=1);


namespace AML\Application\Command;

use AML\Application\Bus\Command;
use AML\Application\Bus\CommandHandler;
use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\ValueObject\SearchUrl;

class SearchUrlChangedHandler implements CommandHandler
{
    //REPO a guardar eventos
    public function __construct()
    {

    }
    //haciendo de guardar eventos
    /** @param SearchUrlChangedCommand $command */
    public function handle(Command $command): void
    {
        var_dump('Hola');
        // TODO: Implement handle() method.
    }
}
