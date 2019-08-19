<?php

namespace AML\Domain\Exception;

abstract class Exception extends \Exception
{
    protected $message;
    protected $meta;

    public function __construct(string $message, array $meta = [])
    {
        $this->message = $message;
        $this->meta = $meta;
        parent::__construct($message);
    }

    public function meta(): array
    {
        return $this->meta;
    }
}
