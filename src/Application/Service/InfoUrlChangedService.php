<?php

namespace AML\Application\Service;

use AML\Domain\Repository\InfoUrlChangeRepository;
use AML\Domain\ValueObject\EventUrlChangedCollection;

class InfoUrlChangedService
{

    /** @var InfoUrlChangeRepository */
    private $infoUrlChangeRepository;

    public function __construct(
        InfoUrlChangeRepository $infoUrlChangeRepository
    )
    {
        $this->infoUrlChangeRepository = $infoUrlChangeRepository;
    }

    public function __invoke(InfoUrlChangedInput $input): EventUrlChangedCollection
    {
        return new EventUrlChangedCollection($this->infoUrlChangeRepository->getAll($input));
    }
}
