<?php

declare(strict_types=1);


namespace AML\Domain\Event;


abstract class DomainEvent
{
    /** @var string */
    private $domainEventOccurredOn;

    public function __construct()
    {
        $this->domainEventOccurredOn = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
    }

    public final function getDomainEventOccurredOn(): string
    {
        return $this->domainEventOccurredOn;
    }
}
