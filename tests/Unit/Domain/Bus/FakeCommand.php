<?php

declare(strict_types=1);

namespace AML\Tests\Unit\Domain\Bus;

use AML\Application\Bus\Command;

class FakeCommand implements Command
{
    /** @var string */
    private $foo;

    public function __construct(string $foo)
    {
        $this->foo = $foo;
    }

    public function getFoo(): string
    {
        return $this->foo;
    }
}
