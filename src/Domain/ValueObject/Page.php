<?php

namespace AML\Domain\ValueObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Page
{
    public const URL = 'url';
    public const HEADER = 'header';
    public const REFERENCE = 'reference';

    private $url;
    private $headers;
    private $reference;

    /** @param UuidInterface $reference */
    public function __construct(
        SearchUrl $url,
        SearchUrlHeaderCollection $headers,
        ?object $reference
    )
    {
        $this->url = $url;
        $this->headers = $headers;
        if ($reference instanceof Uuid) {
            $this->reference = PageReference::fromString($reference->toString());
        }
        $this->reference = $reference ?? PageReference::generate();
    }

    public function url(): SearchUrl
    {
        return $this->url;
    }

    public function headers(): SearchUrlHeaderCollection
    {
        return $this->headers;
    }

    public function reference(): PageReference
    {
        if ($this->reference instanceof Uuid) {
            $this->reference = PageReference::fromString($this->reference->toString());
        }
        return $this->reference;
    }

    public function value(): array
    {
        return [
            self::URL => $this->url->value(),
            self::HEADER => $this->headers->values(),
            self::REFERENCE => $this->reference
        ];
    }

    public function __toString(): string
    {
        return '' . json_encode($this->value());
    }
}
