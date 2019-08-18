<?php

declare(strict_types=1);

namespace AML\Domain\ValueObject;

class EventUrlChanged
{
    /** @var SearchUrl */
    private $url;
    /** @var string */
    private $occurredOn;

    public function __construct(SearchUrl $url, string $occurredOn)
    {
        $this->url = $url;
        $this->occurredOn = $occurredOn;
    }

    public function url(): SearchUrl
    {
        return $this->url;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }
}
