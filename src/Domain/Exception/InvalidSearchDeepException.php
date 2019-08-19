<?php

namespace AML\Domain\Exception;

class InvalidSearchDeepException extends Exception
{
    public function __construct(int $deep)
    {
        parent::__construct('Invalid search Deep', ['deep' => $deep]);
    }
}
