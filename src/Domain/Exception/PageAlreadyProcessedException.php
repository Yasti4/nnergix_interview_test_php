<?php

namespace AML\Domain\Exception;

class PageAlreadyProcessedException extends Exception
{
    public function __construct(string $url)
    {
        parent::__construct('Page already processed and didn\'t change header last modified',
            ['url' => $url]
        );
    }
}
