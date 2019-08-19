<?php

declare(strict_types=1);

namespace AML\Tests\Unit\Domain\Event;

use AML\Domain\Event\{DomainEventListener, DomainEvent};

final class SpyDomainEventListener implements DomainEventListener
{
    /** @var DomainEvent */
    private $domainEvent;
    private $options;

    public function handle(DomainEvent $event, array $options = []): void
    {
        $this->domainEvent = $event;
        $this->options = $options;
    }

    public function isSubscribedTo(DomainEvent $event): bool
    {
        return true;
    }

    public function domainEvent(): DomainEvent
    {
        return $this->domainEvent;
    }
}
