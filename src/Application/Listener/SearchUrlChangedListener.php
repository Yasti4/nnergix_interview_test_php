<?php

declare(strict_types=1);

namespace AML\Application\Listener;

use AML\Application\Bus\CommandExecution;
use AML\Application\Bus\CommandHandler;
use AML\Application\Command\SearchUrlChangedCommand;
use AML\Domain\Event\DomainEvent;
use AML\Domain\Event\DomainEventListener;
use AML\Domain\Event\SearchUrlChangedCreated;
use AML\Domain\Exception\InvalidSearchUrlException;

class SearchUrlChangedListener implements DomainEventListener
{
    /** @var CommandExecution */
    private $commandExecution;

    /** @var CommandHandler $commandHandler */
    public function __construct(CommandExecution $commandExecution)
    {
        $this->commandExecution = $commandExecution;
    }

    /**
     * @throws InvalidSearchUrlException
     */
    public function handle(DomainEvent $domainEvent, array $options = []): void
    {
        /** @var SearchUrlChangedCreated $domainEvent */
        $this->commandExecution->publish(new SearchUrlChangedCommand(
        /** @psalm-suppress $domainEvent */
            $domainEvent->url(),
            $domainEvent->getDomainEventOccurredOn()
        ),
            $options
        );
    }

    public function isSubscribedTo(DomainEvent $domainEvent): bool
    {
        return $domainEvent instanceof SearchUrlChangedCreated;
    }
}
