<?php

declare(strict_types=1);


namespace AML\Application\Command;

use AML\Application\Bus\Command;
use AML\Application\Bus\CommandHandler;
use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\Repository\InfoUrlChangeRepository;
use AML\Domain\ValueObject\EventUrlChanged;
use AML\Domain\ValueObject\SearchUrl;

class SearchUrlChangedHandler implements CommandHandler
{
    /** @var InfoUrlChangeRepository */
    private $infoUrlChangeRepository;

    public function __construct(InfoUrlChangeRepository $infoUrlChangeRepository)
    {
        $this->infoUrlChangeRepository = $infoUrlChangeRepository;
    }

    /** @param SearchUrlChangedCommand $command
     * @throws InvalidSearchUrlException
     */
    public function handle(Command $command): void
    {
        $this->infoUrlChangeRepository->persist(
            new EventUrlChanged(
                new SearchUrl($command->getUrl()),
                $command->getOccurredOn()
            )
        );
    }
}
