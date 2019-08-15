<?php

namespace AML\Domain\Exception;

class SearchUrlNotFoundException extends Exception
{
    public function __construct(string $url)
    {
        parent::__construct('Search url not found', ['url' => $url ]);
    }
}
