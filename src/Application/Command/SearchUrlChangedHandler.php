<?php

declare(strict_types=1);


namespace AML\Application\Command;

use AML\Application\Bus\Command;
use AML\Application\Bus\CommandHandler;
use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\Repository\EventUrlChangedRepository;
use AML\Domain\ValueObject\EventUrlChanged;
use AML\Domain\ValueObject\SearchUrl;

class SearchUrlChangedHandler implements CommandHandler
{
    /** @var EventUrlChangedRepository */
    private $eventUrlChangedRepository;

    public function __construct(EventUrlChangedRepository $eventUrlChangedRepository)
    {
        $this->eventUrlChangedRepository = $eventUrlChangedRepository;
    }

    /** @param SearchUrlChangedCommand $command
     * @throws InvalidSearchUrlException
     */
    public function handle(Command $command): void
    {
        $this->eventUrlChangedRepository->persist(
            new EventUrlChanged(
                new SearchUrl($command->getUrl()),
                $command->getOccurredOn()
            )
        );
    }
}
