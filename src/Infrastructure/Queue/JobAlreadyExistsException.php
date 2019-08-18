<?php

declare(strict_types=1);

namespace AML\Infrastructure\Queue;

use AML\Domain\Exception\Exception;

class JobAlreadyExistsException extends Exception
{
    public function __construct($id)
    {
        parent::__construct(
            'job_already_exists',
            ['id' => $id],
        );
    }
}
