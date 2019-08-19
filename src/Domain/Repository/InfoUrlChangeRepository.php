<?php

namespace AML\Domain\Repository;

use AML\Application\Service\InfoUrlChangedInput;
use AML\Domain\ValueObject\EventUrlChanged;

interface InfoUrlChangeRepository
{
    public function persist(EventUrlChanged $eventUrlChange): void;

    /** @return  EventUrlChanged[] */
    public function getAll(InfoUrlChangedInput $changedInput): array;
}
