<?php

namespace AML\Application\Bus;

use AML\Domain\ValueObject\SearchUrl;

class ProcessPageCommand implements Command
{
    /** @var SearchUrl */
    private $url;

    public function __construct(string $url)
    {
        $this->url = new SearchUrl($url);
    }
}
