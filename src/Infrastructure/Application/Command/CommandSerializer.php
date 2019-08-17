<?php

declare(strict_types=1);

namespace AML\Infrastructure\Application\Command;

use AML\Application\Bus\Command;

interface CommandSerializer
{
    public function serialize(Command $command): string;

    public function deserialize(string $data): Command;
}
