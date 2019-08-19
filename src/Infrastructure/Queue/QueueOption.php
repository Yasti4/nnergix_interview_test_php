<?php

declare(strict_types=1);

namespace AML\Infrastructure\Queue;

use MyCLabs\Enum\Enum;

/**
 * @method static QueueOption QUEUE_NAME()
 */
final class QueueOption extends Enum
{
    protected const QUEUE_NAME = 'QUEUE_NAME';
}
