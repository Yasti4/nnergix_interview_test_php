<?php

declare(strict_types=1);

namespace AML\Infrastructure\Queue;

use AML\Domain\Exception\Exception;

class JobNotExistsException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'job_not_exists',
            []
        );
    }
}
