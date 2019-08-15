<?php

namespace AML\Domain\Exception;

class InvalidSearchUrlException extends Exception
{
    public function __construct(string $url)
    {
        parent::__construct('Invalid search Url', ['url' => $url ]);
    }
}
