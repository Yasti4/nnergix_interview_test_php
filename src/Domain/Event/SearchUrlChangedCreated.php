<?php

declare(strict_types=1);


namespace AML\Domain\Event;

class SearchUrlChangedCreated extends DomainEvent
{
    /** @var string $url */
    private $url;

    public function __construct(string $url)
    {
        parent::__construct();

        $this->url = $url;
    }

    public function url(): string
    {
        return $this->url;
    }
}
