<?php

declare(strict_types=1);


namespace AML\Domain\Event;


interface DomainEventListener
{
    public function handle(DomainEvent $domainEvent): void;

    public function isSubscribedTo(DomainEvent $domainEvent): bool;
}
