<?php

declare(strict_types=1);

namespace AML\Application\Command;

use AML\Application\Bus\Command;
use AML\Domain\Exception\InvalidSearchUrlException;
use AML\Domain\ValueObject\SearchUrl;

class SearchUrlChangedCommand implements Command
{
    /** @var SearchUrl */
    private $url;
    /** @var string */
    private $occurredOn;

    /** @throws InvalidSearchUrlException */
    public function __construct(string $url, string $occurredOn)
    {
        $this->url = new SearchUrl($url);
        $this->occurredOn = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
    }

    public function getUrl(): string //TODO: class o simple value?
    {
        return $this->url->value();
    }

    public function getOccurredOn(): string
    {
        return $this->occurredOn;
    }
}
