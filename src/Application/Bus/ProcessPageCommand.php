<?php

namespace AML\Application\Bus;

class ProcessPageCommand implements Command
{
    public function __construct(string $url)
    {
        $this->url = new SearchUrl($url);
    }
}
