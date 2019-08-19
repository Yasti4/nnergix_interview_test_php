<?php

declare(strict_types=1);

namespace AML\Tests\Unit\Domain\Event;

use AML\Domain\Event\DomainEvent;

class FakeDomainEvent extends DomainEvent
{
    /** @var string */
    private $test;

    public function __construct(string $test = 'hello-world')
    {
        parent::__construct();

        $this->test = $test;
    }

    public function test(): string
    {
        return $this->test;
    }
}
