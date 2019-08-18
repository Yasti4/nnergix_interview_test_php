<?php

declare(strict_types=1);

namespace AML\Infrastructure\Queue;

use AML\Domain\Exception\Exception;

class NothingToConsumeException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'nothing_to_consume',
            []
        );
    }
}
