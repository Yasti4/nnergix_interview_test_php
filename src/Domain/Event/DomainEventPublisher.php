<?php

declare(strict_types=1);


namespace AML\Domain\Event;


final class DomainEventPublisher
{
    /** @var null|DomainEventPublisher */
    private static $instance = null;
    /** @var DomainEventListener[] */
    private $listeners;
    /** @var int */
    private $id;

    public static function instance(DomainEventListener ...$listeners): self
    {
        if (static::$instance === null) {
            static::$instance = new self(...$listeners);
        }

        return static::$instance;
    }

    private function __construct(DomainEventListener ...$listeners)
    {
        $this->listeners = [];
        $this->id = 0;

        foreach ($listeners as $listener) {
            $this->subscribe($listener);
        }
    }

    public function subscribe(DomainEventListener $listener): int
    {
        $newId = $this->id++;
        $this->listeners[$newId] = $listener;

        return $newId;
    }

    public function ofId(int $id): ?DomainEventListener
    {
        return $this->listeners[$id] ?? null;
    }

    public function unsubscribe(int $id): void
    {
        unset($this->listeners[$id]);
    }

    public function publish(DomainEvent $event, array $options = []): void
    {
        foreach ($this->listeners as $listener) {
            if ($listener->isSubscribedTo($event)) {
                $listener->handle($event, $options);
            }
        }
    }

    public static function clear()
    {
        static::$instance = null;
    }
}
