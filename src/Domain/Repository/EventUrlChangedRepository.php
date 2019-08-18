<?php

namespace AML\Domain\Repository;

use AML\Domain\ValueObject\EventUrlChanged;

interface EventUrlChangedRepository
{
    public function persist(EventUrlChanged $eventUrlChange): void;
    
}
